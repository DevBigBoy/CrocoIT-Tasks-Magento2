<?php

namespace Croco\Job\Controller\Adminhtml\Job;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Croco\Job\Model\JobFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Croco_Job::job_delete';

    /**
     * @var JobFactory
     */
    protected $jobFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param JobFactory $jobFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        JobFactory $jobFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->jobFactory = $jobFactory;
        $this->logger = $logger;
    }

    /**
     * Execute delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id'); // Confirm this parameter name
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $job = $this->jobFactory->create()->load($id);

                if (!$job->getId()) {
                    throw new LocalizedException(__('Job does not exist.'));
                }

                $job->delete();
                $this->messageManager->addSuccessMessage(__('Job deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting the job.'));
                $this->logger->critical($e);
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        $this->messageManager->addErrorMessage(__('Job does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}
