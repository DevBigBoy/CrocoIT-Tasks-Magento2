<?php

namespace Croco\Job\Model\ResourceModel\Department;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Job\Model\Department as DepartmentModel;
use Croco\Job\Model\ResourceModel\Department as DepartmentResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(DepartmentModel::class, DepartmentResource::class);
    }
}
