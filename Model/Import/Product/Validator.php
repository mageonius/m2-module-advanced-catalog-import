<?php
/**
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

namespace Mageon\AdvancedCatalogImport\Model\Import\Product;

use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;
use Magento\CatalogImportExport\Model\Import\Product\Validator as CatalogImportExportValidator;
use Mageon\AdvancedCatalogImport\Helper\Data;

/**
 * Class Validator
 * @package Mageon\AdvancedCatalogImport\Model\Import\Product
 */
class Validator extends CatalogImportExportValidator
{
    /** @var Data */
    private $helper;

    /**
     * Validator constructor.
     * @param Data $helper
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param array $validators
     */
    public function __construct(Data $helper, \Magento\Framework\Stdlib\StringUtils $string, $validators = [])
    {
        parent::__construct($string, $validators);
        $this->helper = $helper;
    }

    /**
     * Override for isAttributeValid method in order to make possible to add new EAV option values.
     *
     * @param string $attrCode
     * @param array $attrParams
     * @param array $rowData
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function isAttributeValid($attrCode, array $attrParams, array $rowData)
    {
        if(!$this->helper->isModuleEnabled()) {
            return parent::isAttributeValid($attrCode, $attrParams, $rowData);
        }

        if (!$this->isRequiredAttributeValid($attrCode, $attrParams, $rowData)) {
            $valid = false;
            $this->_addMessages(
                [
                    sprintf(
                        $this->context->retrieveMessageTemplate(
                            RowValidatorInterface::ERROR_VALUE_IS_REQUIRED
                        ),
                        $attrCode
                    )
                ]
            );
            return $valid;
        }

        if ($attrParams['type'] === 'select' || $attrParams['type'] === 'multiselect') {
            return true;
        }

        if ($attrParams['type'] === 'boolean' && \in_array((string)$rowData[$attrCode], ['0', '1'], true)) {
            return true;
        }

        return parent::isAttributeValid($attrCode, $attrParams, $rowData);
    }
}
