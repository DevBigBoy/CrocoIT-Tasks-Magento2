<?php

namespace Croco\Job\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Croco\Job\Model\DepartmentFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Croco_Job::department_save';

    /**
     * @var DepartmentFactory
     */
    protected $departmentFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param DepartmentFactory $departmentFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DepartmentFactory $departmentFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->departmentFactory = $departmentFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Execute save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');
            $department = $this->departmentFactory->create();

            if ($id) {
                $department->load($id);
                if (!$department->getId()) {
                    $this->messageManager->addErrorMessage(__('This department no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $department->setData($data);

            $this->_eventManager->dispatch(
                'croco_job_department_prepare_save',
                ['department' => $department, 'request' => $this->getRequest()]
            );

            try {
                $department->save();
                $this->messageManager->addSuccessMessage(__('Department saved successfully.'));
                $this->dataPersistor->clear('department_data');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $department->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the department.'));
                $this->_logger->critical($e);
            }

            $this->dataPersistor->set('department_data', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
