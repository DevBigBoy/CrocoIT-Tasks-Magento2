<?php
namespace Croco\Job\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Croco\Job\Model\ResourceModel\Department\CollectionFactory;

class Department implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $departmentCollectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $departmentCollectionFactory
     */
    public function __construct(CollectionFactory $departmentCollectionFactory)
    {
        $this->departmentCollectionFactory = $departmentCollectionFactory;
    }

    /**
     * Get options in the format of [value => label] pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->departmentCollectionFactory->create();
        $options = [];

        foreach ($collection as $department) {
            $options[] = [
                'value' => $department->getId(),
                'label' => $department->getName(),
            ];
        }

        return $options;
    }
}
