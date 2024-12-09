<?php
namespace Croco\Articles\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class LinkArticlesToCategories implements DataPatchInterface
{
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->insertMultiple(
            $this->moduleDataSetup->getTable('croco_articles_article_category'),
            [
                ['article_id' => 1, 'category_id' => 1], // The Future of AI -> Technology
                ['article_id' => 1, 'category_id' => 3], // The Future of AI -> AI (sub-category)
                ['article_id' => 2, 'category_id' => 2], // Healthy Living Tips -> Health
                ['article_id' => 3, 'category_id' => 1], // Top 10 Gadgets of 2024 -> Technology
            ]
        );
    }

    public static function getDependencies()
    {
        return [AddArticles::class, AddCategories::class];
    }

    public function getAliases()
    {
        return [];
    }
}
