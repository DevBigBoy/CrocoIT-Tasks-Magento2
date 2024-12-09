<?php

namespace Croco\Job\Ui\DataProvider\Department;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Croco\Job\Model\ResourceModel\Department\CollectionFactory;
use Magento\Framework\Api\Filter;
class DataProvider extends AbstractDataProvider
{
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    public function addFilter(Filter $filter)
    {
        if ($filter->getField() === 'fulltext') {
            $this->getCollection()->addFieldToFilter(
                ['name', 'description'], // Specify the columns for full-text search
                [
                    ['like' => '%' . $filter->getValue() . '%'],
                    ['like' => '%' . $filter->getValue() . '%']
                ]
            );
        } else {
            parent::addFilter($filter);
        }
    }

    public function getData()
    {
        if (!$this->collection->isLoaded()) {
            $this->collection->load();
        }

        $items = $this->collection->toArray();
        return [
            'totalRecords' => $this->collection->getSize(),
            'items' => $items['items']
        ];
    }
}
