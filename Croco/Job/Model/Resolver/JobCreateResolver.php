<?php


namespace Croco\Job\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Croco\Job\Model\JobFactory;
use Magento\Framework\Exception\LocalizedException;

class JobCreateResolver implements ResolverInterface
{
    /**
     * @var JobFactory
     */
    private $jobFactory;

    public function __construct(JobFactory $jobFactory)
    {
        $this->jobFactory = $jobFactory;
    }

    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $input = $args['input'];

        if (empty($input['title']) || empty($input['department_id'])) {
            throw new LocalizedException(__('Required fields missing.'));
        }

        $job = $this->jobFactory->create();
        $job->setData($input);
        $job->save();

        return [
            'id' => $job->getId(),
            'title' => $job->getTitle(),
            'type' => $job->getType(),
            'location' => $job->getLocation(),
            'date' => $job->getDate(),
            'status' => $job->getStatus(),
            'department_id' => $job->getDepartmentId(),
            'description' => $job->getDescription()
        ];
    }
}
