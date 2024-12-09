<?php
declare(strict_types=1);

namespace Croco\Articles\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Category extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        // Initializes the table name and primary key field for the model
        $this->_init('croco_articles_category', 'category_id');
    }
}
