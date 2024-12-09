<?php
namespace Croco\Articles\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Croco\Articles\Model\Category;
use Magento\Framework\App\Request\DataPersistorInterface;
use Psr\Log\LoggerInterface;

class Save extends Action
{
    const ADMIN_RESOURCE = 'Croco_Articles::categories_save';

    /**
     * @var Category
     */
    protected $categoryModel;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param Category $categoryModel
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        Category $categoryModel,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->categoryModel = $categoryModel;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
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
     * Execute the save action
     *
     * @return Redirect
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        // Return to the category list if no data is provided
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        try {
            // Initialize the category model and load data if updating an existing category
            $categoryId = $this->getRequest()->getParam('category_id');
            $category = $this->categoryModel;
 //           dd($categoryId);
            if ($categoryId) {
                $category->load($categoryId);
                if (!$category->getId()) {
                    $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            // Set parent_id to NULL if "No Parent" is selected
//            $data['parent_id'] = $data['parent_id'] ?: null;
            $data['parent_id'] = $data['parent_id'] ?? null;

//            dd($data);
            // Populate category model with data and log data for debugging
            $category->setData($data);
            $this->logger->info('Category Data:', $category->getData());

            // Dispatch event
            $this->_eventManager->dispatch(
                'croco_articles_category_save_prepare',
                ['category' => $category, 'request' => $this->getRequest()]
            );

            // Save category and clear form data from session
            $category->save();
            $this->messageManager->addSuccessMessage(__('Category saved successfully.'));
            $this->dataPersistor->clear('croco_articles_category');

            // Redirect based on the "Save and Continue" parameter
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $category->getId(), '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');

        } catch (LocalizedException $e) {
            $this->logger->error('LocalizedException: ' . $e->getMessage());
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Exception: ' . $e->getMessage());
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the category.'));
        }

        // Preserve form data in the session to prepopulate the form after an error
        $this->dataPersistor->set('croco_articles_category', $data);

        return $resultRedirect->setPath('*/*/edit', ['category_id' => $categoryId ?? $category->getId()]);
    }
}
