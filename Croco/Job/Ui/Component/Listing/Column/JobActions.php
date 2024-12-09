<?php
namespace Croco\Job\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class JobActions
 * Adds "Edit" and "Delete" actions to each row in the Job listing.
 */
class JobActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * Adds Edit and Delete actions for each row in the Job listing grid.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    $itemId = $item['entity_id'];

                    // Edit action
                    $item[$this->getData('name')]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'croco_job/job/edit', // Updated route to match the custom 'croco_job' route
                            ['id' => $itemId]
                        ),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];


                    // Delete action
                    $item[$this->getData('name')]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'croco_job/job/delete', // Updated route to match the custom 'croco_job' route
                            ['id' => $itemId]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete Job'),
                            'message' => __('Are you sure you want to delete the job with ID %1?', $itemId),
                        ],
                        'hidden' => false,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
