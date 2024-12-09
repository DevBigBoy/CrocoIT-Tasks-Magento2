<?php

namespace Croco\Job\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Croco\Job\Model\DepartmentFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Croco_Job::department_delete';

    /**
     * @var DepartmentFactory
     */
    protected $departmentFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param DepartmentFactory $departmentFactory
     */
    public function __construct(
        Action\Context $context,
        DepartmentFactory $departmentFactory
    ) {
        parent::__construct($context);
        $this->departmentFactory = $departmentFactory;
    }

    /**
     * Execute delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $department = $this->departmentFactory->create()->load($id);

                if (!$department->getId()) {
                    throw new LocalizedException(__('Department does not exist.'));
                }

                $department->delete();
                $this->messageManager->addSuccessMessage(__('Department deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting the department.'));
                $this->_logger->critical($e);
            }

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }

        $this->messageManager->addErrorMessage(__('Department does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}
