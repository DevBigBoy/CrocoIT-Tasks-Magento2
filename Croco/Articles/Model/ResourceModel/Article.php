<?php
namespace Croco\Articles\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Article extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('croco_articles_article', 'article_id');
    }
}
