<?php
declare(strict_types=1);

namespace Croco\Articles\Ui\DataProvider\Article\Form;

use Croco\Articles\Model\ResourceModel\Article\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class ArticleDataProvider extends AbstractDataProvider
{
    public function __construct(
        CollectionFactory $collectionFactory,
                          $name,
                          $primaryFieldName,
                          $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (!$this->collection->isLoaded()) {
            $this->collection->load();
        }

        $items = $this->collection->getItems();
        $data = [];
        foreach ($items as $item) {
            $data[$item->getId()] = $item->getData();
        }
        return $data;
    }
}
