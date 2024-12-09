<?php
namespace Croco\Articles\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Croco\Articles\Model\CategoryFactory;
use Croco\Articles\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Croco_Articles::categories_delete';

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param CategoryFactory $categoryFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        Action\Context $context,
        CategoryFactory $categoryFactory,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        parent::__construct($context);
        $this->categoryFactory = $categoryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Check if the user is allowed to access this controller
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Execute delete action
     *
     * @return Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $categoryId = $this->getRequest()->getParam('category_id');

        if (!$categoryId) {
            $this->messageManager->addErrorMessage(__('Category ID is missing.'));
            return $resultRedirect->setPath('*/*/');
        }

        // Load the category
        $category = $this->categoryFactory->create()->load($categoryId);

        if (!$category->getId()) {
            $this->messageManager->addErrorMessage(__('This category no longer exists.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            // Check for subcategories
            if ($this->hasSubcategories($categoryId)) {
                $this->messageManager->addErrorMessage(__('This category has subcategories and cannot be deleted.'));
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $categoryId]);
            }

            // Delete the category
            $category->delete();
            $this->messageManager->addSuccessMessage(__('Category deleted successfully.'));

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the category.'));
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check if a category has subcategories
     *
     * @param int $categoryId
     * @return bool
     */
    private function hasSubcategories($categoryId)
    {
        $subcategoriesCount = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', $categoryId)
            ->count();

        return $subcategoriesCount > 0;
    }
}
