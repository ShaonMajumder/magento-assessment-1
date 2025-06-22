<?php

namespace Strativ\ProductTags\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Strativ\ProductTags\Model\ResourceModel\ProductTags\CollectionFactory;

class TagDataProvider extends AbstractDataProvider
{
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData()
    {
        $items = [];
        foreach ($this->collection as $tag) {
            $items[] = [
                'tag_id' => $tag->getId(),
                'product_id' => $tag->getProductId(),
                'tag_name' => $tag->getTag()
            ];
        }
        return [
            'totalRecords' => $this->collection->getSize(),
            'items' => $items
        ];
    }
}