<?php
namespace Croco\Job\Api\Data;

interface JobInterface
{
    const ENTITY_ID = 'entity_id';
    const TITLE = 'title';
    const TYPE = 'type';
    const LOCATION = 'location';
    const DATE = 'date';
    const STATUS = 'status';
    const DESCRIPTION = 'description';
    const DEPARTMENT_ID = 'department_id';

    /**
     * Get Job ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set Job ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get Type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set Type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get Location
     *
     * @return string|null
     */
    public function getLocation();

    /**
     * Set Location
     *
     * @param string $location
     * @return $this
     */
    public function setLocation($location);

    /**
     * Get Date
     *
     * @return string|null
     */
    public function getDate();

    /**
     * Set Date
     *
     * @param string $date
     * @return $this
     */
    public function setDate($date);

    /**
     * Get Status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set Status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set Description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get Department ID
     *
     * @return int|null
     */
    public function getDepartmentId();

    /**
     * Set Department ID
     *
     * @param int $departmentId
     * @return $this
     */
    public function setDepartmentId($departmentId);
}
