<?php
namespace Paytech\PaytechCommerce\Model;

/**
 * @api
 * @since 100.0.2
 */
class Environment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'prod', 'label' => __('Production')], ['value' => 'test', 'label' => __('Teste')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['prod' => __('Production'), 'test' => __('Teste')];
    }
}
