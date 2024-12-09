<?php

namespace Croco\Articles\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Croco\Articles\Model\ResourceModel\Article\CollectionFactory as ArticleCollectionFactory;
use Croco\Articles\Model\ResourceModel\ArticleCategory\CollectionFactory as ArticleCategoryCollectionFactory;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Articles extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var ArticleCollectionFactory
     */
    protected $articleCollectionFactory;

    /**
     * @var ArticleCategoryCollectionFactory
     */
    protected $articleCategoryCollectionFactory;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param ArticleCollectionFactory $articleCollectionFactory
     * @param ArticleCategoryCollectionFactory $articleCategoryCollectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ArticleCollectionFactory $articleCollectionFactory,
        ArticleCategoryCollectionFactory $articleCategoryCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->articleCollectionFactory = $articleCollectionFactory;
        $this->articleCategoryCollectionFactory = $articleCategoryCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();

        // Get the selected category ID from the query parameter
        $categoryId = $this->getRequest()->getParam('category_id', null);

        // Get articles filtered by category if category_id is present
        $articles = $this->getArticlesWithCategories($categoryId);

        // Get categories as a nested tree
        $categoriesTree = $this->getCategoriesTree();

        // Pass data to the block
        $resultPage->getLayout()->getBlock('croco_articles_block')
            ->setData('articles', $articles)
            ->setData('categoriesTree', $categoriesTree)
            ->setData('selectedCategoryId', $categoryId); // Pass selected category ID to template if needed

        return $resultPage;
    }

    /**
     * Fetch all articles, optionally filtered by a specific category
     *
     * @param int|null $categoryId
     * @return array
     */
    private function getArticlesWithCategories($categoryId = null)
    {
        $articlesData = [];
        $articleCollection = $this->articleCollectionFactory->create();

        // If categoryId is specified, join with category table to filter articles by category
        if ($categoryId) {
            $articleCollection->getSelect()->join(
                ['relation' => 'croco_articles_article_category'],
                'main_table.article_id = relation.article_id',
                []
            )->where('relation.category_id = ?', $categoryId);
        }

        foreach ($articleCollection as $article) {
            $categoryCollection = $this->articleCategoryCollectionFactory->create()
                ->addFieldToFilter('article_id', $article->getId())
                ->join(
                    ['category' => 'croco_articles_category'],
                    'main_table.category_id = category.category_id',
                    ['category_id', 'name', 'description', 'parent_id']
                );

            $categoriesData = [];
            foreach ($categoryCollection as $category) {
                $categoriesData[] = [
                    'category_id' => $category->getCategoryId(),
                    'name' => $category->getName(),
                    'description' => $category->getDescription(),
                    'parent_id' => $category->getParentId()
                ];
            }

            $articlesData[] = [
                'article_id' => $article->getId(),
                'title' => $article->getTitle(),
                'short_description' => $article->getShortDescription(),
                'body' => $article->getBody(),
                'image' => $article->getImage(),
                'published_at' => $article->getPublishedAt(),
                'status' => $article->getStatus(),
                'categories' => $categoriesData
            ];
        }

        return $articlesData;
    }

//    /**
//     * Fetch all articles with their associated categories
//     *
//     * @return array
//     */
//    private function getArticlesWithCategories()
//    {
//        $articlesData = [];
//        $articleCollection = $this->articleCollectionFactory->create();
//
//        foreach ($articleCollection as $article) {
//            $categoryCollection = $this->articleCategoryCollectionFactory->create()
//                ->addFieldToFilter('article_id', $article->getId())
//                ->join(
//                    ['category' => 'croco_articles_category'],
//                    'main_table.category_id = category.category_id',
//                    ['category_id', 'name', 'description', 'parent_id']
//                );
//
//            $categoriesData = [];
//            foreach ($categoryCollection as $category) {
//                $categoriesData[] = [
//                    'category_id' => $category->getCategoryId(),
//                    'name' => $category->getName(),
//                    'description' => $category->getDescription(),
//                    'parent_id' => $category->getParentId()
//                ];
//            }
//
//            $articlesData[] = [
//                'article_id' => $article->getId(),
//                'title' => $article->getTitle(),
//                'short_description' => $article->getShortDescription(),
//                'body' => $article->getBody(),
//                'image' => $article->getImage(),
//                'published_at' => $article->getPublishedAt(),
//                'status' => $article->getStatus(),
//                'categories' => $categoriesData
//            ];
//        }
//
//        return $articlesData;
//    }

    /**
     * Build a nested category tree
     *
     * @return array
     */
    private function getCategoriesTree()
    {
        $categoriesData = [];
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addFieldToSelect(['category_id', 'name', 'description', 'parent_id']);

        foreach ($categoryCollection as $category) {
            $categoriesData[] = [
                'category_id' => $category->getCategoryId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'parent_id' => $category->getParentId()
            ];
        }

        return $this->buildCategoryTree($categoriesData);
    }

    /**
     * Convert flat category data to nested tree
     *
     * @param array $categories
     * @return array
     */
    private function buildCategoryTree(array $categories)
    {
        $categoryById = [];
        $tree = [];

        foreach ($categories as &$category) {
            $category['children'] = [];
            $categoryById[$category['category_id']] = &$category;
        }

        foreach ($categoryById as &$category) {
            if ($category['parent_id'] && isset($categoryById[$category['parent_id']])) {
                $categoryById[$category['parent_id']]['children'][] = &$category;
            } else {
                $tree[] = &$category;
            }
        }

        return $tree;
    }
}
