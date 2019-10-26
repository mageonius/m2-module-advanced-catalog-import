<?php
/**
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

declare(strict_types=1);

namespace Mageon\AdvancedCatalogImport\Plugin\ImportExport\Block\Adminhtml\Import\Edit;

use Magento\Framework\Data\Form;
use Magento\ImportExport\Block\Adminhtml\Import\Edit\Form as FormBlock;
use Mageon\AdvancedCatalogImport\Model\AdvancedProductsImport;
use Mageon\AdvancedCatalogImport\Helper\Data;

class FormPlugin
{
    /**
     * This value is hardcoded in order to avoid complexity
     * @var string
     */
    public const BEHAVIOR_ID = 'basic_behavior';

    public const FIELDSET_ID = self::BEHAVIOR_ID . '_mageon_advanced_catalog_import_fieldset';

    /** @var Data */
    private $helper;

    /**
     * FormPlugin constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param FormBlock $subject
     * @param Form $form
     * @return array
     */
    //@codingStandardsIgnoreLine $subject is never used
    public function beforeSetForm(FormBlock $subject, Form $form): array
    {
        if($this->helper->isModuleEnabled()) {
            $form = $this->addFormFields($form);
        }

        return [$form];
    }

    /**
     * @param Form $form
     * @return Form
     */
    private function addFormFields(Form $form): Form {
        $fieldset = $form->addFieldset(
            self::FIELDSET_ID,
            ['legend' => __('Advanced Catalog Import'), 'class' => 'no-display'],
            self::BEHAVIOR_ID . '_fieldset'
        );

        $fieldset->addField(self::BEHAVIOR_ID . '_' . AdvancedProductsImport::FIELD_ALLOW_NEW_OPTIONS,
            'checkbox',
            [
                'name' => AdvancedProductsImport::FIELD_ALLOW_NEW_OPTIONS,
                'label' => __('Allow to add new attribute options'),
                'title' => __('Allow to add new attribute options'),
                'value' => 1
            ]
        );

        $fieldset->addField(self::BEHAVIOR_ID . '_' . AdvancedProductsImport::FIELD_ALLOW_ALTERNATE_BOOLEAN_VALUES,
            'checkbox',
            [
                'name' => AdvancedProductsImport::FIELD_ALLOW_ALTERNATE_BOOLEAN_VALUES,
                'label' => __('Allow to use alternate boolean values'),
                'title' => __('Allow to use alternate boolean values'),
                'value' => 1
            ]
        );

        return $form;
    }
}
