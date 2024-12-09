<?php
declare(strict_types=1);

namespace Croco\Articles\Model\ResourceModel\ArticleCategory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Articles\Model\ArticleCategory;
use Croco\Articles\Model\ResourceModel\ArticleCategory as ArticleCategoryResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ArticleCategory::class, ArticleCategoryResource::class);
    }
}
