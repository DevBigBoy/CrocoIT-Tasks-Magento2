<?php
declare(strict_types=1);
namespace Croco\Articles\Model;

use Croco\Articles\Api\Data\CategoryInterface;
use Magento\Framework\Model\AbstractModel;

class Category extends AbstractModel implements CategoryInterface
{
    protected function _construct()
    {
        $this->_init(\Croco\Articles\Model\ResourceModel\Category::class);
    }

    /**
     * Get articles associated with the category.
     *
     * @return \Croco\Articles\Model\ResourceModel\Article\Collection
     */
    public function getArticles()
    {
        $articleCollection = $this->articleCollectionFactory->create();
        $articleCollection->getSelect()->join(
            ['relation' => 'croco_articles_article_category'],
            'main_table.article_id = relation.article_id',
            []
        )->where('relation.category_id = ?', $this->getId());

        return $articleCollection;
    }

    public function getId() { return $this->getData(self::CATEGORY_ID); }
    public function setId($id) { return $this->setData(self::CATEGORY_ID, $id); }

    public function getName() { return $this->getData(self::NAME); }
    public function setName($name) { return $this->setData(self::NAME, $name); }

    public function getDescription() { return $this->getData(self::DESCRIPTION); }
    public function setDescription($description) { return $this->setData(self::DESCRIPTION, $description); }

    public function getParentId() { return $this->getData(self::PARENT_ID); }
    public function setParentId($parentId) { return $this->setData(self::PARENT_ID, $parentId); }
}
