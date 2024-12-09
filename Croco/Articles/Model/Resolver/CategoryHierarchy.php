<?php

namespace Croco\Articles\Model\Resolver;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class CategoryHierarchy implements ResolverInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Constructor
     *
     * @param CategoryRepository $categoryRepository
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryRepository        $categoryRepository,
        CategoryCollectionFactory $categoryCollectionFactory
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Resolve function for GraphQL
     *
     * @param array $args
     * @return array
     * @throws GraphQlInputException
     */
    public function resolve(\Magento\Framework\GraphQl\Config\Element\Field $field, $context, $info, array $value = null, array $args = null)
    {
        if (!isset($args['categoryId']) || !is_int($args['categoryId'])) {
            throw new GraphQlInputException(__('Category ID is required and should be an integer.'));
        }

        $categoryId = $args['categoryId'];

        try {
            $rootCategory = $this->categoryRepository->get($categoryId);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlInputException(__('Category with ID %1 does not exist.', $categoryId));
        }

        return $this->buildCategoryHierarchy($rootCategory);
    }

    /**
     * Build a recursive category hierarchy structure
     *
     * @param \Magento\Catalog\Api\Data\CategoryInterface $category
     * @return array
     */
    private function buildCategoryHierarchy($category)
    {
        $categoryData = [
            'id' => (int)$category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
            'parent_id' => (int)$category->getParentId(),
            'children' => []
        ];

        $childCategories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['id', 'name', 'description', 'parent_id'])
            ->addAttributeToFilter('parent_id', $category->getId())
            ->addIsActiveFilter();

        foreach ($childCategories as $childCategory) {
            $categoryData['children'][] = $this->buildCategoryHierarchy($childCategory);
        }

        return $categoryData;
    }
}
