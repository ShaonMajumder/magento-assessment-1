<?php

namespace Strativ\ProductTags\Model\ResourceModel\ProductTags;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection 
{
    protected function _construct() 
    {
        $this->_init(
            \Strativ\ProductTags\Model\ProductTags::class,
            \Strativ\ProductTags\Model\ResourceModel\ProductTags::class
        );
    }
}