<?php
namespace Croco\Job\Model;

use Magento\Framework\Model\AbstractModel;
use Croco\Job\Api\Data\DepartmentInterface;
use Croco\Job\Model\ResourceModel\Department as DepartmentResource;

class Department extends AbstractModel implements DepartmentInterface
{
    protected $_eventPrefix = 'croco_job_department';
    protected $_eventObject = 'department';
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(DepartmentResource::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }
}
