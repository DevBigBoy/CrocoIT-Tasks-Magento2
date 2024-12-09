<?php

namespace Croco\Job\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Job\Model\ResourceModel\Department\CollectionFactory as DepartmentCollectionFactory;

class GetAllDepartments implements ResolverInterface
{
    private $departmentCollectionFactory;

    public function __construct(DepartmentCollectionFactory $departmentCollectionFactory)
    {
        $this->departmentCollectionFactory = $departmentCollectionFactory;
    }

    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $departmentCollection = $this->departmentCollectionFactory->create();
        $departmentCollection->addFieldToSelect(['entity_id', 'name', 'description']);

        $departments = [];
        foreach ($departmentCollection as $department) {
            $departments[] = [
                'entity_id' => $department->getId(),
                'name' => $department->getName(),
                'description' => $department->getDescription(),
            ];
        }

        return $departments;
    }
}
