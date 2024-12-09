<?php
declare(strict_types=1);

namespace Croco\Articles\Api;

use Croco\Articles\Api\Data\ArticleInterface;

interface ArticleRepositoryInterface
{
    /**
     * Save an article and assign categories to it.
     *
     * @param \Croco\Articles\Api\Data\ArticleInterface $article
     * @param array $categoryIds
     * @return \Croco\Articles\Api\Data\ArticleInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ArticleInterface $article, array $categoryIds): ArticleInterface;

    /**
     * Get categories associated with a given article ID.
     *
     * @param int $articleId
     * @return int[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoriesByArticleId(int $articleId): array;

    /**
     * Retrieve an article by its ID.
     *
     * @param int $articleId
     * @return \Croco\Articles\Api\Data\ArticleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $articleId): ArticleInterface;

    /**
     * Delete an article.
     *
     * @param \Croco\Articles\Api\Data\ArticleInterface $article
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ArticleInterface $article): bool;

    /**
     * Delete an article by its ID.
     *
     * @param int $articleId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $articleId): bool;
}
