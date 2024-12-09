<?php
namespace Croco\Articles\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddArticles implements DataPatchInterface
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
            $this->moduleDataSetup->getTable('croco_articles_article'),
            [
                [
                    'title' => 'The Future of AI',
                    'short_description' => 'An overview of AI advancements',
                    'body' => 'Full article content about AI advancements...',
                    'image' => 'ai_future.jpg',
                    'published_at' => '2024-01-01 10:00:00',
                    'status' => 1
                ],
                [
                    'title' => 'Healthy Living Tips',
                    'short_description' => 'Tips for a healthier life',
                    'body' => 'Full article content on healthy living...',
                    'image' => 'health_tips.jpg',
                    'published_at' => '2024-02-01 10:00:00',
                    'status' => 1
                ],
                [
                    'title' => 'Top 10 Gadgets of 2024',
                    'short_description' => 'A review of the best gadgets',
                    'body' => 'Full article content on 2024 gadgets...',
                    'image' => 'gadgets.jpg',
                    'published_at' => '2024-03-01 10:00:00',
                    'status' => 1
                ],
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
