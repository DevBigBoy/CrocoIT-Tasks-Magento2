<?php
declare(strict_types=1);

namespace Croco\Articles\Model;

use Magento\Framework\Model\AbstractModel;

class ArticleCategory extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Croco\Articles\Model\ResourceModel\ArticleCategory::class);
    }
}
