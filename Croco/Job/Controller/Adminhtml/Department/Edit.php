<?php

namespace Croco\Job\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Croco\Job\Model\DepartmentFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;

class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Croco_Job::department_save';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var DepartmentFactory
     */
    protected $departmentFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $coreRegistry
     * @param DepartmentFactory $departmentFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        DepartmentFactory $departmentFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->departmentFactory = $departmentFactory;
    }

    /**
     * Initialize the edit action with breadcrumbs and layout settings
     *
     * @return Page
     */
    protected function _initAction()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Croco_Job::department')
            ->addBreadcrumb(__('Department'), __('Department'))
            ->addBreadcrumb(__('Manage Departments'), __('Manage Departments'));

        return $resultPage;
    }

    /**
     * Execute method for editing a department
     *
     * @return Page|Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $department = $this->departmentFactory->create();

        if ($id) {
            $department->load($id);
            if (!$department->getId()) {
                $this->messageManager->addErrorMessage(__('This department no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $department->setData($data);
        }

        $this->coreRegistry->register('current_department', $department);

        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Department') : __('New Department'),
            $id ? __('Edit Department') : __('New Department')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Departments'));
        $resultPage->getConfig()->getTitle()->prepend($department->getId() ? $department->getName() : __('New Department'));

        return $resultPage;
    }
}
