<?php
namespace Croco\Articles\Block\Adminhtml\Category\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

class Save implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * Constructor
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Retrieve button configuration
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Category'),         // The label for the save button
            'class' => 'save primary',              // CSS classes for styling
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']], // Initialize as a save button
            ],
            'sort_order' => 10,                     // Controls order among other buttons
        ];
    }
}
