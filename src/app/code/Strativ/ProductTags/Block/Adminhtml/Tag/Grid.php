<?php

namespace Strativ\ProductTags\Block\Adminhtml\Tag;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Strativ\ProductTags\Model\ResourceModel\ProductTags\CollectionFactory;

class Grid extends Extended
{
    protected $collectionFactory;

    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        // Define your grid columns here
        return parent::_prepareColumns();
    }
}
