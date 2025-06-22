<?php

namespace Strativ\ProductTags\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * TagActions column for Product Tags grid.
 */
class TagActions extends Column
{
    /**
     * Prepare Data Source for the grid.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $item[$name]['delete'] = [
                    'href' => $this->getContext()->getUrlBuilder()->getUrl('producttags/tag/delete', ['id' => $item['id']]),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete Tag'),
                        'message' => __('Are you sure you want to delete this tag?')
                    ],
                    'post' => true
                ];
            }
        }
        return $dataSource;
    }
}