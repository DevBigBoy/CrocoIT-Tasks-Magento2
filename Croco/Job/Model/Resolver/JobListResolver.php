<?php

namespace Croco\Job\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Job\Model\ResourceModel\Job\CollectionFactory as JobCollectionFactory;

class JobListResolver implements ResolverInterface
{
    private $jobCollectionFactory;

    public function __construct(JobCollectionFactory $jobCollectionFactory)
    {
        $this->jobCollectionFactory = $jobCollectionFactory;
    }

    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $collection = $this->jobCollectionFactory->create();
        $jobs = [];

        foreach ($collection as $job) {
            $jobs[] = [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                'type' => $job->getType(),
                'location' => $job->getLocation(),
                'date' => $job->getDate(),
                'status' => $job->getStatus(),
                'description' => $job->getDescription(),
            ];
        }

        return $jobs;
    }
}
