<?php

namespace Croco\Articles\Controller\Adminhtml\Article;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Redirect;
use Croco\Articles\Model\ArticleFactory;
use Croco\Articles\Model\ResourceModel\Article as ArticleResource;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\DB\Adapter\AdapterInterface;

class Save extends Action
{
    protected $articleFactory;
    protected $articleResource;
    protected $uploaderFactory;
    protected $mediaDirectory;
    protected $dateTime;

    public function __construct(
        Action\Context $context,
        ArticleFactory $articleFactory,
        ArticleResource $articleResource,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        DateTime $dateTime
    ) {
        parent::__construct($context);
        $this->articleFactory = $articleFactory;
        $this->articleResource = $articleResource;
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->dateTime = $dateTime;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $articleId = $this->getRequest()->getParam('article_id');
        $article = $this->articleFactory->create();

        if ($articleId) {
            $this->articleResource->load($article, $articleId);
            if (!$article->getId()) {
                $this->messageManager->addErrorMessage(__('This article no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        // Set or update basic article data
        $article->addData($data);

        // Handle Published Date
        if (!$articleId) {
            $article->setCreatedAt($this->dateTime->gmtDate());
        }

        // Handle Image Upload
        if (!empty($_FILES['image']['name'])) {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $path = $this->mediaDirectory->getAbsolutePath('croco_articles/');
                $result = $uploader->save($path);
                $article->setImage('croco_articles/' . $result['file']);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Image upload failed.'));
            }
        } else {
            if (isset($data['image']['value'])) {
                $article->setImage($data['image']['value']);
            }
        }

        // Save the Article and Categories
        try {
            $this->articleResource->save($article);

            // Update category relationships
            $categoryIds = !empty($data['category_ids']) ? $data['category_ids'] : [];
            $this->updateCategoryAssociations($article->getId(), $categoryIds);

            $this->messageManager->addSuccessMessage(__('The article has been saved.'));
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the article.'));
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Updates category associations for the given article.
     *
     * @param int $articleId
     * @param array $categoryIds
     * @throws \Exception
     */
    protected function updateCategoryAssociations($articleId, array $categoryIds)
    {
        $connection = $this->articleResource->getConnection();
        $tableName = $this->articleResource->getTable('croco_articles_article_category');

        // Remove existing category relations
        $connection->delete($tableName, ['article_id = ?' => $articleId]);

        // Insert new category relations
        if (!empty($categoryIds)) {
            $data = [];
            foreach ($categoryIds as $categoryId) {
                $data[] = ['article_id' => $articleId, 'category_id' => $categoryId];
            }
            $connection->insertMultiple($tableName, $data);
        }
    }
}
