<?php
namespace Croco\Articles\Ui\DataProvider;

use Croco\Articles\Model\ResourceModel\Article\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class ArticleDataProvider extends AbstractDataProvider
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

    public function getData()
    {
        return $this->collection->toArray();
    }
}
