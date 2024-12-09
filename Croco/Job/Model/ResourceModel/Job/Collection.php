<?php
namespace Croco\Job\Model\ResourceModel\Job;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Croco\Job\Model\Job as JobModel;
use Croco\Job\Model\ResourceModel\Job as JobResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(JobModel::class, JobResource::class);
    }

    public function addStatusFilter($job, $department){
        $this->addFieldToSelect('*')
            ->addFieldToFilter('status', $job->getEnableStatus())
            ->join(
                array('department' => $department->getResource()->getMainTable()),
                'main_table.department_id = department.'.$department->getIdFieldName(),
                array('department_name' => 'name')
            );
//        dd($this->getSelect()->__toString());
        return $this;
    }
}
