<?php
namespace Croco\Job\Block\Adminhtml\Job\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Croco\Job\Model\Source\Job\Status;
use Croco\Job\Model\Source\Department;

class Form extends Generic
{
    /**
     * @var Status
     */
    protected $statusSource;

    /**
     * @var Department
     */
    protected $departmentSource;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Status $statusSource
     * @param Department $departmentSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Status $statusSource,
        Department $departmentSource,
        array $data = []
    ) {
        $this->statusSource = $statusSource;
        $this->departmentSource = $departmentSource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Initialize Job form
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('job_form');
        $this->setTitle(__('Job Information'));
    }

    /**
     * Prepare form fields
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Croco\Job\Model\Job $model */
        $model = $this->_coreRegistry->registry('jobs_job');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
        ]);

        $form->setHtmlIdPrefix('job_');

        // General Information Fieldset
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        // Title Field
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true
            ]
        );

        // Type Field
        $fieldset->addField(
            'type',
            'text',
            [
                'name' => 'type',
                'label' => __('Type'),
                'title' => __('Type'),
                'required' => true
            ]
        );

        // Location Field
        $fieldset->addField(
            'location',
            'text',
            [
                'name' => 'location',
                'label' => __('Location'),
                'title' => __('Location'),
                'required' => true
            ]
        );

        // Date Field
        if (!$model->getId()) {
            $model->setDate(date('Y-m-d')); // Default to today's date if creating a new record
        }
        $fieldset->addField(
            'date',
            'date',
            [
                'name' => 'date',
                'label' => __('Date'),
                'title' => __('Date'),
                'required' => false,
                'date_format' => 'Y-MM-dd'
            ]
        );

        // Status Field
        if (!$model->getId()) {
            $model->setStatus(Status::STATUS_ENABLED); // Default to enabled status for new jobs
        }
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'values' => $this->statusSource->toOptionArray()
            ]
        );

        // Description Field
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => true
            ]
        );

        // Department Field
        $fieldset->addField(
            'department_id',
            'select',
            [
                'name' => 'department_id',
                'label' => __('Department'),
                'title' => __('Department'),
                'required' => true,
                'values' => $this->departmentSource->toOptionArray()
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
