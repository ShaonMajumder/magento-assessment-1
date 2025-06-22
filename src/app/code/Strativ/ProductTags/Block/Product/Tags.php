<?php

namespace Strativ\ProductTags\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Strativ\ProductTags\Model\Repository\ProductTagsRepository;

/**
 * Block for displaying product tags on the frontend.
 */
class Tags extends Template
{
    /**
     * @var ProductTagsRepository
     */
    private $productTagsRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param Template\Context $context
     * @param ProductTagsRepository $productTagsRepository
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductTagsRepository $productTagsRepository,
        Registry $registry,
        array $data = []
    ) {
        $this->productTagsRepository = $productTagsRepository;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get tags for the current product.
     *
     * @return array
     */
    public function getProductTags()
    {
        $product = $this->registry->registry('current_product');
        if (!$product) {
            return [];
        }
        $tags = $this->productTagsRepository->getByProductId($product->getId());
        return array_column($tags, 'tag');
    }
}
