<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Darsh\Catalog\Ui\DataProvider\Product\Form\Modifier;

/**
 * Class Review
 */
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form;
use Magento\Framework\UrlInterface;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Framework\App\ObjectManager;

/**
 * Review modifier for catalog product form
 *
 * @api
 * @since 100.1.0
 */
class Review extends \Magento\Review\Ui\DataProvider\Product\Form\Modifier\Review
{    
    protected $locator;
    protected $urlBuilder;
    private $moduleManager;

    
    public function modifyMeta(array $meta)
    {
        if (!$this->locator->getProduct()->getId() || !$this->getModuleManager()->isOutputEnabled('Magento_Review')) {
            return $meta;
        }

        $meta[static::GROUP_REVIEW] = [
            'children' => [
                'review_listing' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'review_listing',
                                'externalProvider' => 'review_listing.review_listing_data_source',
                                'selectionsProvider' => 'review_listing.review_listing.product_columns.ids',
                                'ns' => 'review_listing',
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => false,
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.current_product_id'
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.current_product_id'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Product Reviews'),
                        'collapsible' => true,
                        'opened' => true,
                        'componentType' => Form\Fieldset::NAME,
                        'sortOrder' =>
                            $this->getNextGroupSortOrder(
                                $meta,
                                static::GROUP_CONTENT,
                                static::SORT_ORDER
                            ),
                    ],
                ],
            ],
        ];

        return $meta;
    }
    
    private function getModuleManager()
    {
        if ($this->moduleManager === null) {
            $this->moduleManager = ObjectManager::getInstance()->get(ModuleManager::class);
        }
        return $this->moduleManager;
    }    
}
