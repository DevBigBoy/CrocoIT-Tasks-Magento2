<?php

declare(strict_types=1);

namespace Croco\Articles\Model\ResourceModel\Article;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Articles\Model\Article;
use Croco\Articles\Model\ResourceModel\Article as ArticleResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Article::class, ArticleResource::class);
    }
}
