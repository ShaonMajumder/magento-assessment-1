<?php

namespace Strativ\ProductTags\Controller\Tag;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Strativ\ProductTags\Model\ResourceModel\ProductTags\CollectionFactory as TagCollectionFactory;

/**
 * Controller for displaying products by tag.
 */
class View extends Action
{
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var TagCollectionFactory
     */
    private $tagCollectionFactory;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param ProductCollectionFactory $productCollectionFactory
     * @param TagCollectionFactory $tagCollectionFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory,
        TagCollectionFactory $tagCollectionFactory,
        Registry $registry
    ) {
        parent::__construct($context);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->tagCollectionFactory = $tagCollectionFactory;
        $this->registry = $registry;
    }

    /**
     * Display products associated with a specific tag.
     *
     * @return \Magento\Framework\Controller\Result\Page
     */
    public function execute()
    {
        $tag = $this->getRequest()->getParam('tag');
        if (!$tag) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('noroute');
        }

        $tagCollection = $this->tagCollectionFactory->create()
            ->addFieldToFilter('tag', $tag);
        $productIds = array_column($tagCollection->getItems(), 'product_id');

        if (empty($productIds)) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('noroute');
        }

        $productCollection = $this->productCollectionFactory->create()
            ->addFieldToFilter('entity_id', ['in' => $productIds]);

        $this->registry->register('product_collection', $productCollection);
        $this->registry->register('current_tag', $tag);

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Products with Tag: %1', $tag));
        return $resultPage;
    }
}