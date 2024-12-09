<?php
namespace Croco\Articles\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory;

class CategoryList implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $categoryCollectionFactory
     */
    public function __construct(CollectionFactory $categoryCollectionFactory)
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Get options for the Parent Category dropdown
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [['value' => '', 'label' => __('No Parent')]]; // Default option for no parent
        $categories = $this->categoryCollectionFactory->create()
            ->addFieldToSelect(['category_id', 'name', 'parent_id'])
            ->setOrder('parent_id', 'ASC')
            ->setOrder('name', 'ASC');

        $categoryTree = [];
        foreach ($categories as $category) {
            $categoryTree[$category->getParentId()][] = $category;
        }

        $this->buildOptions($options, $categoryTree);
        return $options;
    }

    /**
     * Recursive function to build hierarchical options array
     *
     * @param array $options
     * @param array $categoryTree
     * @param int|null $parentId
     * @param int $level
     */
    private function buildOptions(&$options, $categoryTree, $parentId = null, $level = 0)
    {
        if (!isset($categoryTree[$parentId])) {
            return;
        }

        foreach ($categoryTree[$parentId] as $category) {
            $options[] = [
                'value' => $category->getId(),
                'label' => str_repeat('--', $level) . ' ' . $category->getName(),
            ];
            $this->buildOptions($options, $categoryTree, $category->getId(), $level + 1);
        }
    }
}
