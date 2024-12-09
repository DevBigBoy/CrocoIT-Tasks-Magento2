<?php
namespace Croco\Job\Model\ResourceModel\Tag;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Job\Model\Tag as TagModel;
use Croco\Job\Model\ResourceModel\Tag as TagResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(TagModel::class, TagResource::class);
    }
}
