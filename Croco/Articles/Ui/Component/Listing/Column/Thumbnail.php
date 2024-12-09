<?php

namespace Croco\Articles\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Thumbnail extends Column
{
    /**
     * Default alt field for the image.
     */
    const ALT_FIELD = 'title';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Thumbnail constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepares the data source for displaying the image thumbnail.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$fieldName]) && $item[$fieldName]) {
                    $filename = $item[$fieldName];
                    $item[$fieldName . '_src'] = $this->getImageUrl($filename);
                    $item[$fieldName . '_alt'] = $this->getAltText($item, $filename);
                    $item[$fieldName . '_orig_src'] = $this->getImageUrl($filename);
                }
            }
        }

        return $dataSource;
    }

    /**
     * Builds the complete URL for the image.
     *
     * @param string $imagePath
     * @return string
     */
    private function getImageUrl(string $imagePath): string
    {
        return $imagePath ? $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)  . $imagePath : '';
    }

    /**
     * Retrieves the alt text for the image.
     *
     * @param array $row
     * @param string $defaultAlt
     * @return string
     */
    private function getAltText(array $row, string $defaultAlt): string
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return $row[$altField] ?? $defaultAlt;
    }
}
