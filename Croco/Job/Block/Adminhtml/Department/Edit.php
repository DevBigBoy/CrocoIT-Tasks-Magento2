<?php

namespace Croco\Job\Block\Adminhtml\Department;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
	/**
	 * Initialize the Department Edit Block
	 */
	protected function _construct()
	{
		$this->_objectId = 'entity_id'; // Primary key field ID for the department
		$this->_blockGroup = 'Croco_Job'; // Module name
		$this->_controller = 'adminhtml_department'; // Controller path

		parent::_construct();

		// Add the default "Save" button
		$this->buttonList->update('save', 'label', __('Save Department'));

		// Add "Save and Continue Edit" button
		$this->buttonList->add(
			'save_and_continue',
			[
				'label' => __('Save and Continue Edit'),
				'class' => 'save',
				'data_attribute' => [
					'mage-init' => ['button' => ['event' => 'saveAndContinueEdit']]
				]
			],
			-100 // Sort order to place button before other buttons
		);

		// Update the "Back" button
		$this->buttonList->update('back', 'label', __('Back to Department List'));
	}

	/**
	 * Get header text for the edit page
	 *
	 * @return string
	 */
	public function getHeaderText()
	{
		$department = $this->_coreRegistry->registry('current_department');
		if ($department && $department->getId()) {
			return __("Edit Department '%1'", $this->escapeHtml($department->getName()));
		} else {
			return __('New Department');
		}
	}

	/**
	 * Get the URL for the "Save and Continue Edit" button
	 *
	 * @return string
	 */
	protected function _getSaveAndContinueUrl()
	{
		return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit']);
	}
}
