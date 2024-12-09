<?php
namespace Croco\Job\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Croco\Job\Model\JobFactory;
use Croco\Job\Model\DepartmentFactory;

class CreateJobData implements DataPatchInterface
{
    private $jobFactory;
    private $departmentFactory;
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        JobFactory $jobFactory,
        DepartmentFactory $departmentFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->jobFactory = $jobFactory;
        $this->departmentFactory = $departmentFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        // Assuming we already have department data created, fetch department IDs
        $departments = [
            'Engineering' => 1,
            'Marketing' => 2,
            'Sales' => 3,
        ];

        $jobs = [
            [
                'title' => 'Software Engineer',
                'type' => 'Full-time',
                'location' => 'Remote',
                'date' => '2024-01-01',
                'status' => 1,
                'description' => 'Develop and maintain software solutions.',
                'department_id' => $departments['Engineering'],
            ],
            [
                'title' => 'Marketing Specialist',
                'type' => 'Part-time',
                'location' => 'New York',
                'date' => '2024-02-01',
                'status' => 1,
                'description' => 'Plan and execute marketing strategies.',
                'department_id' => $departments['Marketing'],
            ]
        ];

        foreach ($jobs as $data) {
            $job = $this->jobFactory->create();
            $job->setData($data);
            $job->save();
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [
            CreateDepartmentData::class,
        ];
    }
}
