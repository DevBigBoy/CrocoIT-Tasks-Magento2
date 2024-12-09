<?php

namespace Croco\Articles\Ui\DataProvider;

use Croco\Articles\Model\ResourceModel\Category\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class CategoryDataProvider extends AbstractDataProvider
{
    /**
     * @var \Croco\Articles\Model\ResourceModel\Category\Collection
     */
    protected $collection;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
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

    /**
     * Get data for UI Component
     *
     * @return array
     */
    public function getData()
    {
        return $this->collection->toArray();
    }
}
