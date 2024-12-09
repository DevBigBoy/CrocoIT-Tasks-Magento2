<?php
declare(strict_types=1);

namespace Croco\Articles\Model;

use Croco\Articles\Api\ArticleRepositoryInterface;
use Croco\Articles\Api\Data\ArticleInterface;
use Croco\Articles\Model\ResourceModel\Article as ArticleResource;
use Croco\Articles\Model\ResourceModel\ArticleCategory as ArticleCategoryResource;
use Croco\Articles\Model\ResourceModel\ArticleCategory\CollectionFactory as ArticleCategoryCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * @var ArticleResource
     */
    protected $articleResource;

    /**
     * @var ArticleFactory
     */
    protected $articleFactory;

    /**
     * @var ArticleCategoryResource
     */
    protected $articleCategoryResource;

    /**
     * @var ArticleCategoryCollectionFactory
     */
    protected $articleCategoryCollectionFactory;

    public function __construct(
        ArticleResource $articleResource,
        ArticleFactory $articleFactory,
        ArticleCategoryResource $articleCategoryResource,
        ArticleCategoryCollectionFactory $articleCategoryCollectionFactory
    ) {
        $this->articleResource = $articleResource;
        $this->articleFactory = $articleFactory;
        $this->articleCategoryResource = $articleCategoryResource;
        $this->articleCategoryCollectionFactory = $articleCategoryCollectionFactory;
    }

    /**
     * Save an article and assign categories to it.
     *
     * @param ArticleInterface $article
     * @param array $categoryIds
     * @return ArticleInterface
     * @throws CouldNotSaveException
     */
    public function save(ArticleInterface $article, array $categoryIds): ArticleInterface
    {
        try {
            // Save the article
            $this->articleResource->save($article);

            // Clear existing categories
            $articleCategoryCollection = $this->articleCategoryCollectionFactory->create();
            $articleCategoryCollection->addFieldToFilter('article_id', $article->getId());
            foreach ($articleCategoryCollection as $item) {
                $item->delete();
            }

            // Assign new categories
            foreach ($categoryIds as $categoryId) {
                $articleCategory = new ArticleCategory();
                $articleCategory->setData('article_id', $article->getId());
                $articleCategory->setData('category_id', $categoryId);
                $this->articleCategoryResource->save($articleCategory);
            }

            return $article;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save article: %1', $e->getMessage()));
        }
    }

    /**
     * Get categories associated with a given article ID.
     *
     * @param int $articleId
     * @return int[]
     * @throws NoSuchEntityException
     */
    public function getCategoriesByArticleId(int $articleId): array
    {
        $categoryIds = [];
        $articleCategoryCollection = $this->articleCategoryCollectionFactory->create();
        $articleCategoryCollection->addFieldToFilter('article_id', $articleId);

        if ($articleCategoryCollection->getSize() == 0) {
            throw new NoSuchEntityException(__('No categories found for article with ID %1', $articleId));
        }

        foreach ($articleCategoryCollection as $item) {
            $categoryIds[] = (int) $item->getData('category_id');
        }

        return $categoryIds;
    }

    /**
     * Retrieve an article by its ID.
     *
     * @param int $articleId
     * @return ArticleInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $articleId): ArticleInterface
    {
        $article = $this->articleFactory->create();
        $this->articleResource->load($article, $articleId);

        if (!$article->getId()) {
            throw new NoSuchEntityException(__('Article with ID "%1" does not exist.', $articleId));
        }

        return $article;
    }

    /**
     * Delete an article.
     *
     * @param ArticleInterface $article
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ArticleInterface $article): bool
    {
        try {
            $this->articleResource->delete($article);
            return true;
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete article: %1', $e->getMessage()));
        }
    }

    /**
     * Delete an article by its ID.
     *
     * @param int $articleId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $articleId): bool
    {
        $article = $this->getById($articleId);
        return $this->delete($article);
    }
}
