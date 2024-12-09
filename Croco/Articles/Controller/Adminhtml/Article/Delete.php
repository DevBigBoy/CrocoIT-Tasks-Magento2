<?php

namespace Croco\Articles\Controller\Adminhtml\Article;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Croco\Articles\Model\ArticleFactory;
use Croco\Articles\Model\ResourceModel\Article as ArticleResource;
use Croco\Articles\Model\ResourceModel\ArticleCategory as ArticleCategoryResource;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class Delete extends Action
{
    /**
     * @var ArticleFactory
     */
    protected $articleFactory;

    /**
     * @var ArticleResource
     */
    protected $articleResource;

    /**
     * @var ArticleCategoryResource
     */
    protected $articleCategoryResource;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param ArticleFactory $articleFactory
     * @param ArticleResource $articleResource
     * @param ArticleCategoryResource $articleCategoryResource
     * @param Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        ArticleFactory $articleFactory,
        ArticleResource $articleResource,
        ArticleCategoryResource $articleCategoryResource,
        Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->articleFactory = $articleFactory;
        $this->articleResource = $articleResource;
        $this->articleCategoryResource = $articleCategoryResource;
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $articleId = $this->getRequest()->getParam('article_id');

        if ($articleId) {
            try {
                // Load the article
                $article = $this->articleFactory->create();
                $this->articleResource->load($article, $articleId);

                if (!$article->getId()) {
                    throw new LocalizedException(__('This article no longer exists.'));
                }

                // Delete the associated image file if it exists
                if ($article->getImage()) {
                    $this->deleteImageFile($article->getImage());
                }

                // Delete category associations
                $this->deleteArticleCategories($articleId);

                // Delete the article record
                $this->articleResource->delete($article);

                $this->messageManager->addSuccessMessage(__('The article has been deleted.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['article_id' => $articleId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting the article.'));
                return $resultRedirect->setPath('*/*/edit', ['article_id' => $articleId]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Delete the image file
     *
     * @param string $imagePath
     */
    protected function deleteImageFile($imagePath)
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $imageAbsolutePath = $mediaDirectory->getAbsolutePath($imagePath);

        if ($mediaDirectory->isFile($imagePath)) {
            $mediaDirectory->delete($imagePath);
        }
    }

    /**
     * Delete category associations for the article
     *
     * @param int $articleId
     */
    protected function deleteArticleCategories($articleId)
    {
        $connection = $this->articleCategoryResource->getConnection();
        $tableName = $this->articleCategoryResource->getMainTable();

        // Delete entries in the relation table by article_id
        $connection->delete($tableName, ['article_id = ?' => (int)$articleId]);
    }
}
