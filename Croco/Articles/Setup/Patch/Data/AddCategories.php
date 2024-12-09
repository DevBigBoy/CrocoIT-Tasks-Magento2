<?php

declare(strict_types=1);
namespace Croco\Articles\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddCategories implements DataPatchInterface
{
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('croco_articles_category'),
            [
                    ['name' => 'Backend', 'description' => 'backend ', 'parent_id' => null],
                    ['name' => 'Frontend', 'description' => 'frontend', 'parent_id' => null],
                    ['name' => 'Ai', 'description' => 'Ai', 'parent_id' => null],
                    ['name' => 'PHP', 'description' => 'php', 'parent_id' => 1],
                    ['name' => 'mysql', 'description' => 'mysql', 'parent_id' => 1],
                    ['name' => 'frameworks', 'description' => 'frameworks', 'parent_id' => 1],
            ]
        );
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
