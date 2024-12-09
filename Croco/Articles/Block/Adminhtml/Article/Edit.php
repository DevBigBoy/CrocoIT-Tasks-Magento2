<?php

namespace Croco\Articles\Block\Adminhtml\Article;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;

class Edit extends Container
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Article Edit Block
     */
    protected function _construct()
    {
        $this->_objectId = 'article_id';
        $this->_blockGroup = 'Croco_Articles';
        $this->_controller = 'adminhtml_article';

        parent::_construct();

        if ($this->isAllowedAction('Croco_Articles::articles_edit')) {
            $this->buttonList->update('save', 'label', __('Save Article'));
            $this->addSaveAndContinueButton();
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->isAllowedAction('Croco_Articles::articles_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Article'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Add "Save and Continue" button
     */
    protected function addSaveAndContinueButton()
    {
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ],
            ],
            -100
        );
    }

    /**
     * Retrieve the header text for the edit page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $article = $this->coreRegistry->registry('current_article');
        if ($article && $article->getId()) {
            return __("Edit Article '%1'", $this->escapeHtml($article->getTitle()));
        } else {
            return __('New Article');
        }
    }

    /**
     * Get URL for the delete action
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['article_id' => $this->getRequest()->getParam('article_id')]);
    }

    /**
     * Check permission for the specified action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get URL for "Save and Continue" button
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit']);
    }
}
