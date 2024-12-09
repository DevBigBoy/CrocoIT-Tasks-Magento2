<?php

namespace Croco\Articles\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Articles\Model\ResourceModel\Article\CollectionFactory as ArticleCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

class ArticlesByCategoryId implements ResolverInterface
{
    /**
     * @var ArticleCollectionFactory
     */
    private $articleCollectionFactory;

    /**
     * Constructor
     *
     * @param ArticleCollectionFactory $articleCollectionFactory
     */
    public function __construct(
        ArticleCollectionFactory $articleCollectionFactory
    ) {
        $this->articleCollectionFactory = $articleCollectionFactory;
    }

    /**
     * Resolve articlesByCategoryId query
     *
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        // Validate the category_id argument
        if (!isset($args['category_id']) || empty($args['category_id'])) {
            throw new LocalizedException(__('Category ID must be provided.'));
        }

        $categoryId = (int)$args['category_id'];

        // Fetch articles associated with the provided category ID
        $articleCollection = $this->articleCollectionFactory->create();
        $articleCollection->getSelect()->join(
            ['relation' => 'croco_articles_article_category'],
            'main_table.article_id = relation.article_id',
            []
        )->where('relation.category_id = ?', $categoryId);

        $articles = [];
        foreach ($articleCollection as $article) {
            $articles[] = [
                'article_id' => $article->getId(),
                'title' => $article->getTitle(),
                'short_description' => $article->getShortDescription(),
                'body' => $article->getBody(),
                'image' => $article->getImage(),
                'published_at' => $article->getPublishedAt(),
                'status' => $article->getStatus()
            ];
        }

        return $articles;
    }
}
