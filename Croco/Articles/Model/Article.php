<?php
declare(strict_types=1);
namespace Croco\Articles\Model;

use Croco\Articles\Api\Data\ArticleInterface;
use Magento\Framework\Model\AbstractModel;

class Article extends AbstractModel implements ArticleInterface
{
    protected function _construct()
    {
        $this->_init(\Croco\Articles\Model\ResourceModel\Article::class);
    }

    /**
     * Get categories associated with the article.
     *
     * @return \Croco\Articles\Model\ResourceModel\Category\Collection
     */
    public function getCategories()
    {
        // Assuming you have a Category collection factory injected into this model
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->getSelect()->join(
            ['relation' => 'croco_articles_article_category'],
            'main_table.category_id = relation.category_id',
            []
        )->where('relation.article_id = ?', $this->getId());

        return $categoryCollection;
    }
    public function getId() { return $this->getData(self::ARTICLE_ID); }
    public function setId($id) { return $this->setData(self::ARTICLE_ID, $id); }

    public function getTitle() { return $this->getData(self::TITLE); }
    public function setTitle($title) { return $this->setData(self::TITLE, $title); }

    public function getShortDescription() { return $this->getData(self::SHORT_DESCRIPTION); }
    public function setShortDescription($shortDescription) { return $this->setData(self::SHORT_DESCRIPTION, $shortDescription); }

    public function getBody() { return $this->getData(self::BODY); }
    public function setBody($body) { return $this->setData(self::BODY, $body); }

    public function getImage() { return $this->getData(self::IMAGE); }
    public function setImage($image) { return $this->setData(self::IMAGE, $image); }

    public function getPublishedAt() { return $this->getData(self::PUBLISHED_AT); }
    public function setPublishedAt($publishedAt) { return $this->setData(self::PUBLISHED_AT, $publishedAt); }

    public function getStatus() { return $this->getData(self::STATUS); }
    public function setStatus($status) { return $this->setData(self::STATUS, $status); }
}
