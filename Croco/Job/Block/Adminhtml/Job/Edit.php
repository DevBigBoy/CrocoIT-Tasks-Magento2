<?php
namespace Croco\Job\Block\Adminhtml\Job;

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
     * Initialize Job Edit Block
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Croco_Job';
        $this->_controller = 'adminhtml_job';

        parent::_construct();

        if ($this->isAllowedAction('Croco_Job::job_save')) {
            $this->buttonList->update('save', 'label', __('Save Job'));
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
        $job = $this->coreRegistry->registry('jobs_job');
        if ($job && $job->getId()) {
            return __("Edit Job '%1'", $this->escapeHtml($job->getTitle()));
        } else {
            return __('New Job');
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
        return $this->getUrl('jobs/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
