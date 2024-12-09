<?php
declare(strict_types=1);

namespace Croco\Articles\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID = 'category_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const PARENT_ID = 'parent_id';

    public function getId();
    public function setId($id);

    public function getName();
    public function setName($name);

    public function getDescription();
    public function setDescription($description);

    public function getParentId();
    public function setParentId($parentId);
}
