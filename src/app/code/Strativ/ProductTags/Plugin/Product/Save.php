<?php

namespace Strativ\ProductTags\Plugin\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Strativ\ProductTags\Model\Repository\ProductTagsRepository;

class Save
{
    private $productTagsRepository;

    public function __construct(ProductTagsRepository $productTagsRepository)
    {
        $this->productTagsRepository = $productTagsRepository;
    }

    public function afterSave(ProductRepositoryInterface $subject, $product)
    {
        $tags = $product->getData('strativ_tags') ?: '';
        $this->productTagsRepository->save($product->getId(), $tags);
        return $product;
    }
}