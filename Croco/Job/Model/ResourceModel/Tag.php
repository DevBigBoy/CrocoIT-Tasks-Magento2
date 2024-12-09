<?php
namespace Croco\Job\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Tag extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('croco_job_tag', 'entity_id');
    }
}
