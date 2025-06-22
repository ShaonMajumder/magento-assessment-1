<?php

namespace Strativ\ProductTags\Model\Repository;

use Strativ\ProductTags\Model\ProductTagsFactory;
use Strativ\ProductTags\Model\ResourceModel\ProductTags as ProductTagsResource;
use Strativ\ProductTags\Model\ResourceModel\ProductTags\CollectionFactory;

class ProductTagsRepository {
    private $productTagsFactory;
    private $productTagsResource;
    private $collectionFactory;

    public function __construct(
        ProductTagsFactory $productTagsFactory,
        ProductTagsResource $productTagsResource,
        CollectionFactory $collectionFactory
    ) {
        $this->productTagsFactory = $productTagsFactory;
        $this->productTagsResource = $productTagsResource;
        $this->collectionFactory = $collectionFactory;
    }

    public function save($productId, $tags) {
        $tags = array_filter(array_map('trim', explode(',', strtolower($tags))));
        $tags = array_map(function($tag) { return preg_replace('/[^a-z0-9-]/', '', $tag); }, $tags);
        $collection = $this->collectionFactory->create()->addFieldToFilter('product_id', $productId);
        foreach ($collection as $item) {
            $this->productTagsResource->delete($item);
        }
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                $model = $this->productTagsFactory->create();
                $model->setData(['product_id' => $productId, 'tag' => $tag]);
                $this->productTagsResource->save($model);
            }
        }
    }
    
    public function getByProductId($productId) {
        $collection = $this->collectionFactory->create()->addFieldToFilter('product_id', $productId);
        return $collection->getItems();
    }
}