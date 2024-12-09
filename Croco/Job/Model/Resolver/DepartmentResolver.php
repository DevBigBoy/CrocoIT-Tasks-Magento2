<?php
namespace Croco\Job\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Job\Model\ResourceModel\Department\CollectionFactory as DepartmentCollectionFactory;

class DepartmentResolver implements ResolverInterface
{
    private $departmentCollectionFactory;

    public function __construct(
        DepartmentCollectionFactory $departmentCollectionFactory
    ) {
        $this->departmentCollectionFactory = $departmentCollectionFactory;
    }

    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // Ensure the user has the proper permissions
        if (!$context->getUserId()) {
            throw new LocalizedException(__('Access denied'));
        }

        // Retrieve department collection
        $collection = $this->departmentCollectionFactory->create();
        $collection->addFieldToSelect(['entity_id', 'name', 'description']);

        $departments = [];
        foreach ($collection as $department) {
            $departments[] = [
                'entity_id' => $department->getId(),
                'name' => $department->getName(),
                'description' => $department->getDescription(),
            ];
        }

        return $departments;
    }
}
