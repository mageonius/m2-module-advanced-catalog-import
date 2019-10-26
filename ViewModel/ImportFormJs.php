<?php
/**
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

declare(strict_types=1);

namespace Mageon\AdvancedCatalogImport\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mageon\AdvancedCatalogImport\Helper\Data;
use Mageon\AdvancedCatalogImport\Plugin\ImportExport\Block\Adminhtml\Import\Edit\FormPlugin;

/**
 * Class ImportFormJs
 * @package Mageon\AdvancedCatalogImport\ViewModel
 */
class ImportFormJs implements ArgumentInterface
{
    /** @var Data */
    private $helper;

    /**
     * ImportFormJs constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool {
        return $this->helper->isModuleEnabled();
    }

    /**
     * @return string
     */
    public function getFieldsetId(): string {
        return FormPlugin::FIELDSET_ID;
    }
}
