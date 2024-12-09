<?php

namespace Croco\Articles\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

class CategoriesTree implements ResolverInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Constructor
     *
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Resolve categoriesTree query
     *
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        // Fetch all categories
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addFieldToSelect(['category_id', 'name', 'description', 'parent_id']);

        // Build a tree structure
        $categories = [];
        foreach ($categoryCollection as $category) {
            $categories[] = [
                'category_id' => $category->getCategoryId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'parent_id' => $category->getParentId()
            ];
        }

        // Organize categories as a nested tree
        $categoryTree = $this->buildCategoryTree($categories);

        return $categoryTree;
    }

    /**
     * Build a nested tree structure from flat category data
     *
     * @param array $categories
     * @return array
     */
    private function buildCategoryTree(array $categories)
    {
        $categoryById = [];
        $tree = [];

        // Initialize the category array with child nodes
        foreach ($categories as $category) {
            $category['children'] = [];
            $categoryById[$category['category_id']] = $category;
        }

        // Build tree by linking children to their respective parent categories
        foreach ($categoryById as $categoryId => &$category) {
            if ($category['parent_id'] && isset($categoryById[$category['parent_id']])) {
                $categoryById[$category['parent_id']]['children'][] = &$category;
            } else {
                // No parent means it's a root category
                $tree[] = &$category;
            }
        }

        return $tree;
    }
}
