<?php
namespace Croco\Job\Controller\Adminhtml\Job;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Croco\Job\Model\Job;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Job model instance
     *
     * @var Job
     */
    protected $jobModel;

    /**
     * Edit constructor
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param Job $jobModel
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        Job $jobModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->jobModel = $jobModel;
        parent::__construct($context);
    }

    /**
     * Check permission via ACL resource
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Croco_Job::job_save');
    }

    /**
     * Initialize action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    protected function initAction()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Croco_Job::job')
            ->addBreadcrumb(__('Job'), __('Job'))
            ->addBreadcrumb(__('Manage Jobs'), __('Manage Jobs'));
        return $resultPage;
    }

    /**
     * Edit Job action
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->jobModel;

        // Load existing job if ID is set
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This job no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }
        }

        // Load form data from session if available
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('jobs_job', $model);

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Job') : __('New Job'),
            $id ? __('Edit Job') : __('New Job')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Jobs'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Job'));

        return $resultPage;
    }
}
