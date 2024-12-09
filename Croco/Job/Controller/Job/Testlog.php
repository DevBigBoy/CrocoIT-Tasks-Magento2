<?php
namespace Croco\Job\Controller\Job;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;

class Testlog extends Action
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * Testlog constructor.
     *
     * @param Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger
    ) {
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * Execute method
     *
     * @return void
     */
    public function execute()
    {
        // Logging messages with different severity levels
        $this->_logger->debug('This is a debug message');
        $this->_logger->info('This is an info message');
        $this->_logger->notice('This is a notice message');
        $this->_logger->warning('This is a warning message');
        $this->_logger->error('This is an error message');
        $this->_logger->critical('This is a critical message');
        $this->_logger->alert('This is an alert message');
        $this->_logger->emergency('This is an emergency message');

        // Load layout and render page
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
