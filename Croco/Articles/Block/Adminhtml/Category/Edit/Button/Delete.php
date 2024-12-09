<?php
namespace Croco\Articles\Block\Adminhtml\Category\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class Delete implements ButtonProviderInterface
{
    protected $urlBuilder;
    protected $request;

    public function __construct(UrlInterface $urlBuilder, RequestInterface $request)
    {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
    }

    /**
     * Get button configuration
     *
     * @return array
     */
    public function getButtonData()
    {
        $categoryId = $this->request->getParam('category_id');

        if (!$categoryId) {
            return []; // Hide button if category_id is not set
        }

        return [
            'label' => __('Delete Category'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this category?') . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }

    /**
     * Get delete URL
     *
     * @return string
     */
    private function getDeleteUrl()
    {
        return $this->urlBuilder->getUrl('*/*/delete', ['category_id' => $this->request->getParam('category_id')]);
    }
}
