<?php
namespace Darsh\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Downloadable\Api\Data\ProductAttributeInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form;

class DownloadablePanel extends \Magento\Downloadable\Ui\DataProvider\Product\Form\Modifier\DownloadablePanel
{
    
    protected $locator;
    protected $arrayManager;
    protected $meta = [];

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $panelConfig['arguments']['data']['config'] = [
            'componentType' => Form\Fieldset::NAME,
            'label' => __('Downloadable Information'),
            'collapsible' => true,
            'opened' => true,
            'sortOrder' => '800',
            'dataScope' => 'data'
        ];
        $this->meta = $this->arrayManager->set('downloadable', $this->meta, $panelConfig);

        $this->addCheckboxIsDownloadable();
        $this->addMessageBox();

        return $this->meta;
    }

    protected function addMessageBox()
    {
        $messagePath = 'downloadable/children/downloadable_message';
        $messageConfig['arguments']['data']['config'] = [
            'componentType' => Container::NAME,
            'component' => 'Magento_Ui/js/form/components/html',
            'additionalClasses' => 'admin__fieldset-note',
            'content' => __('To enable the option set the weight to no'),
            'sortOrder' => 20,
            'visible' => false,
            'imports' => [
                'visible' => '${$.provider}:' . self::DATA_SCOPE_PRODUCT . '.'
                    . ProductAttributeInterface::CODE_HAS_WEIGHT
            ],
        ];

        $this->meta = $this->arrayManager->set($messagePath, $this->meta, $messageConfig);
    }

    protected function addCheckboxIsDownloadable()
    {
        $checkboxPath = 'downloadable/children/' . ProductAttributeInterface::CODE_IS_DOWNLOADABLE;
        $checkboxConfig['arguments']['data']['config'] = [
            'dataType' => Form\Element\DataType\Number::NAME,
            'formElement' => Form\Element\Checkbox::NAME,
            'componentType' => Form\Field::NAME,
            'component' => 'Magento_Downloadable/js/components/is-downloadable-handler',
            'description' => __('Is this downloadable Product?'),
            'dataScope' => ProductAttributeInterface::CODE_IS_DOWNLOADABLE,
            'sortOrder' => 10,
            'imports' => [
                'disabled' => '${$.provider}:' . self::DATA_SCOPE_PRODUCT . '.'
                    . ProductAttributeInterface::CODE_HAS_WEIGHT
            ],
            'valueMap' => [
                'false' => '0',
                'true' => '1',
            ],
            'samplesFieldset' => 'ns = ${ $.ns }, index=container_samples',
            'linksFieldset' => 'ns = ${ $.ns }, index=container_links',
        ];

        $this->meta = $this->arrayManager->set($checkboxPath, $this->meta, $checkboxConfig);
    }
}
