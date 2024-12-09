<?php
declare(strict_types=1);
namespace Croco\Articles\Ui\Component\Category\Form;

use Croco\Articles\Model\Category as CategoryModel;
use Magento\Framework\Data\OptionSourceInterface;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Options tree for "Categories" field in the Croco Articles module
 */
class Options implements OptionSourceInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $categoriesTree;

    /**
     * Constructor
     *
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param RequestInterface $request
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }

    /**
     * Returns an array of categories in a hierarchical tree format.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getCategoriesTree();
    }

    /**
     * Retrieve categories tree with parent-child relationships.
     *
     * @return array
     */
    protected function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            // Initialize category collection
            $matchingNamesCollection = $this->categoryCollectionFactory->create();

            // Select only necessary fields: category_id, name, and parent_id
            $matchingNamesCollection->addFieldToSelect(['category_id', 'name', 'parent_id']);

            $matchingNamesCollection->addFieldToFilter('category_id', ['neq' => null]);

            // Build the category hierarchy based on parent-child relationships
            $categoryById = [];

            foreach ($matchingNamesCollection as $category) {
                $categoryById[$category->getId()] = [
                    'value' => $category->getId(),
                    'label' => $category->getName(),
                    'parent_id' => $category->getParentId(),
                    'optgroup' => []
                ];
            }

            // Organize categories into a tree structure
            $tree = [];
            foreach ($categoryById as $categoryId => &$category) {
                if (isset($categoryById[$category['parent_id']])) {
                    $categoryById[$category['parent_id']]['optgroup'][] = &$category;
                } else {
                    $tree[] = &$category; // This is a top-level category
                }
            }

//            array_unshift($tree, [
//                'value' => '',
//                'label' => 'parent',
//                'parent_id' => null,
//            ]);

            $this->categoriesTree = $tree;

        }

//        dd($this->categoriesTree);

        return $this->categoriesTree;


    }
}
