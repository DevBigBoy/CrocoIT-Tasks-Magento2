<?php
namespace Croco\Job\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Croco\Job\Model\TagFactory;

class CreateTagData implements DataPatchInterface
{
    private $tagFactory;
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        TagFactory $tagFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->tagFactory = $tagFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $tags = [
            ['name' => 'PHP', 'description' => 'PHP development'],
            ['name' => 'Marketing', 'description' => 'Marketing tasks'],
            ['name' => 'Remote', 'description' => 'Remote-friendly positions']
        ];

        foreach ($tags as $data) {
            $tag = $this->tagFactory->create();
            $tag->setData($data);
            $tag->save();
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [];
    }
}
