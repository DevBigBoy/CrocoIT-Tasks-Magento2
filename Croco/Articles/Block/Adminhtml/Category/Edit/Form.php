<?php
namespace Croco\Articles\Block\Adminhtml\Category\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Croco\Articles\Model\Source\CategoryList;

class Form extends Generic
{
    /**
     * @var CategoryList
     */
    protected $categoryListSource;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param CategoryList $categoryListSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        FormFactory $formFactory,
        CategoryList $categoryListSource,
        array $data = []
    ) {
        $this->categoryListSource = $categoryListSource;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Initialize Category form
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('category_form');
        $this->setTitle(__('Category Information'));
    }

    /**
     * Prepare form fields
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Croco\Articles\Model\Category $model */
        $model = $this->_coreRegistry->registry('current_category');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
        ]);

        $form->setHtmlIdPrefix('category_');

        // General Information Fieldset
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model && $model->getId()) {
            $fieldset->addField('category_id', 'hidden', ['name' => 'category_id']);
        }

        // Category Name Field
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true
            ]
        );

        // Category Description Field
        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false
            ]
        );

         // Parent Category Field
        $fieldset->addField(
            'parent_id',
            'select',
            [
                'name' => 'parent_id',
                'label' => __('Parent Category'),
                'title' => __('Parent Category'),
                'values' => $this->categoryListSource->toOptionArray(), // Fetch hierarchical options
                'required' => false
            ]
        );

        // Set form values from the model
        if ($model) {
            $form->setValues($model->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
