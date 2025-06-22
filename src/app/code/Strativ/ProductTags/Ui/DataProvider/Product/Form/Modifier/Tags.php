<?php

namespace Strativ\ProductTags\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Field;
use Strativ\ProductTags\Model\Repository\ProductTagsRepository;
use Psr\Log\LoggerInterface;

class Tags extends AbstractModifier
{
    private $productTagsRepository;
    private $logger;

    public function __construct(
        ProductTagsRepository $productTagsRepository,
        LoggerInterface $logger
    ) {
        $this->productTagsRepository = $productTagsRepository;
        $this->logger = $logger;
    }

    public function modifyData(array $data)
    {
        try {
            foreach ($data as $productId => &$productData) {
                if (!empty($productId)) {
                    $tags = $this->productTagsRepository->getByProductId($productId);
                    $tagNames = array_column($tags, 'tag');
                    $productData['strativ_tags'] = implode(', ', $tagNames);
                    $this->logger->debug('Strativ Tags loaded for product ID ' . $productId . ': ' . implode(', ', $tagNames));
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Error loading Strativ Tags: ' . $e->getMessage());
        }
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $meta['product-details']['children']['strativ_tags'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Strativ Tags'),
                        'formElement' => Field::NAME_INPUT,
                        'componentType' => Field::NAME_FIELD,
                        'dataType' => 'text',
                        'dataScope' => 'strativ_tags',
                        'sortOrder' => 100,
                        'notice' => __('Enter comma-separated tags, e.g., sale, new, featured'),
                        'validation' => [
                            'required-entry' => false,
                        ],
                    ],
                ],
            ],
        ];
        $this->logger->debug('Strativ Tags meta modified');
        return $meta;
    }
}