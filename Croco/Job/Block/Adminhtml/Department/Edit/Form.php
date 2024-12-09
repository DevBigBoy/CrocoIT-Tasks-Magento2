<?php

namespace Croco\Job\Block\Adminhtml\Department\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;

class Form extends Generic
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory
    ) {
        parent::__construct($context, $registry, $formFactory);
    }

    /**
     * Prepare form fields and structure
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        // Initialize form
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
            ]
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        // Get the current department from the registry
        $department = $this->_coreRegistry->registry('current_department');

        // Define the fieldset (form group)
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Department Information'), 'class' => 'fieldset-wide']
        );

        // Add hidden field for entity ID (used when editing an existing department)
        if ($department && $department->getId()) {
            $fieldset->addField(
                'entity_id',
                'hidden',
                ['name' => 'entity_id']
            );
        }

        // Department Name field
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );

        // Department Description field
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false,
            ]
        );

        // Set form values from session or from the loaded department data
        $form->setValues($department ? $department->getData() : []);

        return parent::_prepareForm();
    }
}
