<?php
namespace Croco\Job\Controller\Job;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        // Optional debug line, can be used to check if the controller is reached
        // dd('hello from job Department');

        // Load the layout defined for this controller action (e.g., jobs_job_index.xml)
        $this->_view->loadLayout();

        // Initialize and display any session messages (e.g., success, error messages)
        $this->_view->getLayout()->initMessages();

        // Render the page layout and output the HTML
        $this->_view->renderLayout();
    }
}
