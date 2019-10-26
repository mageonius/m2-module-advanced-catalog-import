<?php
/**
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

declare(strict_types=1);

namespace Mageon\AdvancedCatalogImport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Mageon\AdvancedCatalogImport\Helper
 */
class Data extends AbstractHelper
{
    private const MODULE_XML_PATH = 'mageon_advanced_catalog_import/';

    /**
     * @param null $store
     * @return bool
     */
    public function isModuleEnabled($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::MODULE_XML_PATH . 'general/is_enabled',
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
