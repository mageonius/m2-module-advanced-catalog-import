<?php
/**
 * Copyright © mageOn. All rights reserved.
 * https://mageon.io/
 *
 */

declare(strict_types=1);

namespace Mageon\AdvancedCatalogImport\Plugin\Model\Import\Product\Type;

use Magento\Catalog\Api\ProductAttributeOptionManagementInterface;
use Magento\CatalogImportExport\Model\Import\Product as ImportProduct;
use Magento\CatalogImportExport\Model\Import\Product\Type\AbstractType;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;

/**
 * Class AbstractTypePlugin
 * @package Mageon\AdvancedCatalogImport\Plugin\Model\Import\Product\Type
 */
class AbstractTypePlugin
{
    /**
     * @var ImportProduct
     */
    private $entityModel;

    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var AttributeOptionInterfaceFactory
     */
    private $attributeOptionFactory;

    /**
     * @var ProductAttributeOptionManagementInterface
     */
    private $attributeOptionManagement;

    /**
     * AbstractTypePlugin constructor.
     * @param ImportProduct $entityModel
     * @param ProductAttributeOptionManagementInterface $attributeOptionManagement
     * @param AttributeOptionInterfaceFactory $attributeOptionFactory
     * @param AttributeOptionLabelInterfaceFactory $optionLabelFactory
     */
    public function __construct(
        ImportProduct $entityModel,
        ProductAttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionInterfaceFactory $attributeOptionFactory,
        AttributeOptionLabelInterfaceFactory $optionLabelFactory
    ) {
        $this->entityModel = $entityModel;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
    }

    /**
     * @param AbstractType $subject
     * @param array $rowData
     * @param bool $withDefaultValue
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function beforePrepareAttributesWithDefaultValueForSave(
        AbstractType $subject,
        array $rowData,
        $withDefaultValue = true
    ): array {
        $this->addNewOptions($subject, $rowData);

        return [$rowData, $withDefaultValue];
    }

    /**
     * @param AbstractType $subject
     * @param array $rowData
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    private function addNewOptions(AbstractType $subject, array $rowData): void
    {
        foreach ($rowData as $attrCode => $attrValue) {
            if ($attrValue === '' || $attrValue === null) {
                continue;
            }

            $attributeInfo = $subject->retrieveAttributeFromCache($attrCode);

            if ($attributeInfo === []) {
                continue;
            }

            $attributeType = $attributeInfo['type'];
            if (!\in_array($attributeType, ['select', 'multiselect', 'boolean'], true)) {
                continue;
            }
            $attrId = (int)$attributeInfo['id'];

            if ('multiselect' == $attributeInfo['type']) {
                $optionLabels = $this->entityModel->parseMultiselectValues($attrValue);
            } else {
                $optionLabels = [$attrValue];
            }

            foreach ($optionLabels as $label) {
                $label = trim($label);

                if ($label !== '' && !isset($attributeInfo['options'][strtolower($label)])) {
                    if ($attributeType === 'boolean' && \in_array($label, ['0', '1'], true)) {
                        $subject->addAttributeOption($attrCode, $label, (int)$label);
                    } else {
                        $optionId = str_replace('id_', '', $this->createNewAttributeOption($attrId, $label));
                        if ($optionId) {
                            $subject->addAttributeOption($attrCode, strtolower($label), (int)$optionId);
                        }
                    }
                }
            }
        }
    }


    /**
     * @param int $attrId
     * @param string $label
     * @return string|false
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    private function createNewAttributeOption(int $attrId, string $label)
    {
        /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
        $optionLabel = $this->optionLabelFactory->create();
        $optionLabel->setStoreId(0);
        $optionLabel->setLabel($label);

        /** @var \Magento\Eav\Model\Entity\Attribute\Option $option */
        $option = $this->attributeOptionFactory->create();
        $option->setLabel($label)
            ->setStoreLabels([$optionLabel])
            ->setIsDefault(false)
            ->setSortOrder(0);

        return $this->attributeOptionManagement->add(
            $attrId,
            $option
        );
    }
}