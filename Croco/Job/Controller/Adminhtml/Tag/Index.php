<?php
namespace Croco\Job\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Croco_Job::tag_view';
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Croco_Job::job_management');
        $resultPage->getConfig()->getTitle()->prepend(__('Tags'));
        return $resultPage;
    }
}
