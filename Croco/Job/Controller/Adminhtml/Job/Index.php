<?php

namespace Croco\Job\Controller\Adminhtml\Job;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Croco_Job::job';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute method to render the Job grid page
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Croco_Job::job');
        $resultPage->addBreadcrumb(__('Jobs'), __('Jobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Job Listings'));

        return $resultPage;
    }
}
