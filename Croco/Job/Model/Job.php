<?php
namespace Croco\Job\Model;

use Magento\Framework\Model\AbstractModel;
use Croco\Job\Api\Data\JobInterface;
use Croco\Job\Model\ResourceModel\Job as JobResource;

class Job extends AbstractModel implements JobInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    protected $_eventPrefix = 'croco_job_job';
    protected $_eventObject = 'job';
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(JobResource::class);
    }

    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getLocation()
    {
        return $this->getData(self::LOCATION);
    }

    public function setLocation($location)
    {
        return $this->setData(self::LOCATION, $location);
    }

    public function getDate()
    {
        return $this->getData(self::DATE);
    }

    public function setDate($date)
    {
        return $this->setData(self::DATE, $date);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getDepartmentId()
    {
        return $this->getData(self::DEPARTMENT_ID);
    }

    public function setDepartmentId($departmentId)
    {
        return $this->setData(self::DEPARTMENT_ID, $departmentId);
    }

    /**
     * Retrieve available statuses for the Job model
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            $this->getDisableStatus() => __('Disabled'),
            $this->getEnableStatus() => __('Enabled')
        ];
    }

    /**
     * Get enabled status value
     *
     * @return int
     */
    public function getEnableStatus()
    {
        return self::STATUS_ENABLED;
    }

    /**
     * Get disabled status value
     *
     * @return int
     */
    public function getDisableStatus()
    {
        return self::STATUS_DISABLED;
    }
}
