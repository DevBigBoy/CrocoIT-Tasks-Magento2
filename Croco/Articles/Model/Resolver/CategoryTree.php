<?php
declare(strict_types=1);

namespace Croco\Articles\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Articles\Model\CategoryRepository;

class CategoryTree implements ResolverInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $parentId = $args['parent_id'] ?? null;
        return $this->getCategoryTree($parentId);
    }

    private function getCategoryTree($parentId = null): array
    {
        $collection = $this->categoryRepository->getList();

        if ($parentId === null) {
            $collection->addFieldToFilter('parent_id', ['null' => true]);
        } else {
            $collection->addFieldToFilter('parent_id', ['eq' => $parentId]);
        }

        $categories = [];
        foreach ($collection as $category) {
            $categories[] = [
                'category_id' => $category->getCategoryId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
                'parent_id' => $category->getParentId(),
                'children' => $this->getCategoryTree($category->getCategoryId())
            ];
        }

        return $categories;
    }
}
