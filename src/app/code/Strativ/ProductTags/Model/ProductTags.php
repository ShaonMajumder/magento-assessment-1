<?php

namespace Strativ\ProductTags\Model;

use Magento\Framework\Model\AbstractModel;

class ProductTags extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Strativ\ProductTags\Model\ResourceModel\ProductTags::class);
    }
}