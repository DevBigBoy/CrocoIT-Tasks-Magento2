<?php
namespace Croco\Articles\Ui\DataProvider\Category\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Croco\Articles\Model\ResourceModel\Category\Collection;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory;

class CategoryDataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * CategoryDataProvider constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {

        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data for the UI Component form.
     *
     * @return array
     */
    public function getData()
    {
//        dd('a7a3');

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $category) {
            $this->loadedData[$category->getId()] = $category->getData();
        }

        return $this->loadedData; // Return an empty array if no data is loaded
    }
}
