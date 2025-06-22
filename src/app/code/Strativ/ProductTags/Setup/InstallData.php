<?php

namespace Strativ\ProductTags\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class InstallData implements InstallDataInterface
{
    private $categorySetupFactory;

    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'strativ_tags',
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Strativ Tags',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false
            ]
        );

        $attributeSetId = $categorySetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeGroupId = $categorySetup->getAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY, $attributeSetId, 'General');
        $categorySetup->addAttributeToGroup(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeSetId,
            $attributeGroupId,
            'strativ_tags',
            100
        );

        $setup->endSetup();
    }
}