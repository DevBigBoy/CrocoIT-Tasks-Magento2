<?php
namespace Croco\Job\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Croco\Job\Model\DepartmentFactory;

class CreateDepartmentData implements DataPatchInterface
{
    private $departmentFactory;
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        DepartmentFactory $departmentFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->departmentFactory = $departmentFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $departments = [
            ['name' => 'Engineering', 'description' => 'Engineering department'],
            ['name' => 'Marketing', 'description' => 'Marketing department'],
            ['name' => 'Sales', 'description' => 'Sales department'],
            ['name' => 'IT', 'description' => 'information technology'],
            ['name' => 'Operations', 'description' => 'operations']
        ];

        foreach ($departments as $data) {
            $department = $this->departmentFactory->create();
            $department->setData($data);
            $department->save();
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
