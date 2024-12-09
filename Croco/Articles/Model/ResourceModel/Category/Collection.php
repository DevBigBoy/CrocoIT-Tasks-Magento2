<?php
declare(strict_types=1);

namespace Croco\Articles\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Articles\Model\Category as CategoryModel;
use Croco\Articles\Model\ResourceModel\Category as CategoryResource;

class Collection extends AbstractCollection
{
    /**
     * Initialize collection model and resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(CategoryModel::class, CategoryResource::class);
    }
}
