<?php

namespace Croco\Articles\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Articles\Model\ResourceModel\Article\CollectionFactory as ArticleCollectionFactory;
use Croco\Articles\Model\ResourceModel\ArticleCategory\CollectionFactory as ArticleCategoryCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

class Articles implements ResolverInterface
{
    private $articleCollectionFactory;
    private $articleCategoryCollectionFactory;

    public function __construct(
        ArticleCollectionFactory $articleCollectionFactory,
        ArticleCategoryCollectionFactory $articleCategoryCollectionFactory
    ) {
        $this->articleCollectionFactory = $articleCollectionFactory;
        $this->articleCategoryCollectionFactory = $articleCategoryCollectionFactory;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $articles = [];
        $articleCollection = $this->articleCollectionFactory->create();

        foreach ($articleCollection as $article) {
            $categoryCollection = $this->articleCategoryCollectionFactory->create()
                ->addFieldToFilter('article_id', $article->getId())
                ->join(
                    ['category' => 'croco_articles_category'],
                    'main_table.category_id = category.category_id',
                    ['category_id', 'name', 'description', 'parent_id']
                );

            $categories = [];
            foreach ($categoryCollection as $category) {
                $categories[] = [
                    'category_id' => $category->getCategoryId(),
                    'name' => $category->getName(),
                    'description' => $category->getDescription(),
                    'parent_id' => $category->getParentId()
                ];
            }

            $articles[] = [
                'article_id' => $article->getId(),
                'title' => $article->getTitle(),
                'short_description' => $article->getShortDescription(),
                'body' => $article->getBody(),
                'image' => $article->getImage(),
                'published_at' => $article->getPublishedAt(),
                'status' => $article->getStatus(),
                'categories' => $categories
            ];
        }

        return $articles;
    }
}
