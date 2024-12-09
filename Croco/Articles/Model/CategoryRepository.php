<?php
declare(strict_types=1);

namespace Croco\Articles\Model;

use Croco\Articles\Api\CategoryRepositoryInterface;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Retrieve list of categories.
     *
     * @return \Croco\Articles\Model\ResourceModel\Category\Collection
     */
    public function getList(): \Croco\Articles\Model\ResourceModel\Category\Collection
    {
        $collection = $this->collectionFactory->create();
        return $collection;
    }
}
