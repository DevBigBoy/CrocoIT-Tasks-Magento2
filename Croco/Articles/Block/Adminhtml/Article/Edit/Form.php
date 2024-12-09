<?php

namespace Croco\Articles\Block\Adminhtml\Article\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Croco\Articles\Model\Source\CategoryList;
use Croco\Articles\Model\ResourceModel\ArticleCategory\CollectionFactory as ArticleCategoryCollectionFactory;

class Form extends Generic
{
    protected $wysiwygConfig;
    protected $categoryListSource;
    protected $articleCategoryCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Registry $registry,
        FormFactory $formFactory,
        CategoryList $categoryListSource,
        ArticleCategoryCollectionFactory $articleCategoryCollectionFactory,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    ) {
        $this->categoryListSource = $categoryListSource;
        $this->articleCategoryCollectionFactory = $articleCategoryCollectionFactory;
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Initialize Article form
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('article_form');
        $this->setTitle(__('Article Information'));
    }

    /**
     * Prepare form fields
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Croco\Articles\Model\Article $model */
        $model = $this->_coreRegistry->registry('current_article');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        ]);

        $form->setHtmlIdPrefix('article_');

        // General Information Fieldset
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('article_id', 'hidden', ['name' => 'article_id']);
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

        // Short Description Field
        $fieldset->addField(
            'short_description',
            'textarea',
            [
                'name' => 'short_description',
                'label' => __('Short Description'),
                'title' => __('Short Description'),
                'required' => false
            ]
        );

        // Body Field with WYSIWYG Editor
        $fieldset->addField(
            'body',
            'editor',
            [
                'name' => 'body',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:10em',
                'required' => true,
                'config' => $this->wysiwygConfig->getConfig()
            ]
        );

        // Status Field
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => [
                    ['value' => 1, 'label' => __('Active')],
                    ['value' => 0, 'label' => __('Inactive')]
                ],
                'required' => true
            ]
        );

        // Image Upload Field
        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'required' => false,
                'note' => __('Upload an image for the article.')
            ]
        );

        // Categories Multi-Select Field
        $fieldset->addField(
            'category_ids',
            'multiselect',
            [
                'name' => 'category_ids[]',
                'label' => __('Categories'),
                'title' => __('Categories'),
                'values' => $this->categoryListSource->toOptionArray(),
                'required' => false
            ]
        );

        // Set form values, including selected categories
        $form->setValues(array_merge($model->getData(), ['category_ids' => $this->getSelectedCategoryIds($model)]));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get selected category IDs for the current article
     *
     * @param \Croco\Articles\Model\Article $article
     * @return array
     */
    protected function getSelectedCategoryIds($article)
    {
        if (!$article->getId()) {
            return [];
        }

        // Load associated categories for the article
        $collection = $this->articleCategoryCollectionFactory->create()
            ->addFieldToFilter('article_id', $article->getId());

        $categoryIds = [];
        foreach ($collection as $articleCategory) {
            $categoryIds[] = $articleCategory->getCategoryId();
        }

        return $categoryIds;
    }
}
