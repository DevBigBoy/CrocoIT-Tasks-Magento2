<?php

namespace Croco\Articles\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory;

class ParentCategory extends Column
{
    private $categoryCollectionFactory;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $categoryCollectionFactory,
        array $components = [],
        array $data = []
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (!$item['parent_id']) {
                    $item['parent_name'] = __('Parent Category');
                } else {
                    $item['parent_name'] = $this->getParentCategoryName($item['parent_id']);
                }
            }
        }
        return $dataSource;
    }

    private function getParentCategoryName($parentId)
    {
        $categoryCollection = $this->categoryCollectionFactory->create();
        $parentCategory = $categoryCollection->addFieldToFilter('category_id', $parentId)->getFirstItem();
        return $parentCategory->getId() ? $parentCategory->getName() : __('N/A');
    }
}
