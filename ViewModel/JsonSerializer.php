<?php
/**
 * Copyright © mageOn, 2019.
 * https://github.com/mageonius
 */

declare(strict_types=1);

namespace Mageon\AdvancedCatalogImport\ViewModel;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class JsonSerializer
 * @package Mageon\AdvancedCatalogImport\ViewModel
 */
class JsonSerializer implements ArgumentInterface
{

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param Json $serializer
     */
    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Returns serialized version of data
     *
     * @param array $data
     * @return string
     */
    public function serialize(array $data): string
    {
        return $this->serializer->serialize($data);
    }
}
