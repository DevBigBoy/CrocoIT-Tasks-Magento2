<?php
declare(strict_types=1);

namespace Croco\Articles\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ArticleCategory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('croco_articles_article_category', null); // No primary key since it's a composite key
    }
}
