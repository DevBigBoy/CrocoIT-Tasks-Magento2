<?php
namespace Croco\Articles\Block\Adminhtml\Category;

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
     * Initialize Category Edit Block
     */
    protected function _construct()
    {
        $this->_objectId = 'category_id';
        $this->_blockGroup = 'Croco_Articles';
        $this->_controller = 'adminhtml_category';

        parent::_construct();

        if ($this->isAllowedAction('Croco_Articles::categories_save')) {
            $this->buttonList->update('save', 'label', __('Save Category'));
            $this->addSaveAndContinueButton();
        } else {
            $this->buttonList->remove('save');
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
        $category = $this->coreRegistry->registry('current_category');
        if ($category && $category->getId()) {
            return __("Edit Category '%1'", $this->escapeHtml($category->getName()));
        } else {
            return __('New Category');
        }
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
        return $this->getUrl('croco_articles/category/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
