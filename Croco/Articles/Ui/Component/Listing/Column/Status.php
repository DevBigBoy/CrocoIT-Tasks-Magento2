<?php

namespace Croco\Articles\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Data\OptionSourceInterface;

class Status extends Column
{
    /**
     * Prepares the data source and converts the status to "Active" or "Inactive".
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name'); // Get the column name, e.g., 'status'
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $this->getStatusText($item[$fieldName]);
                }
            }
        }
        return $dataSource;
    }

    /**
     * Convert status value to a readable text.
     *
     * @param int $status
     * @return string
     */
    protected function getStatusText($status)
    {
        return $status ? __('Active') : __('Inactive');
    }
}
