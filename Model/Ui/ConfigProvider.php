<?php
namespace Paytech\PaytechCommerce\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'paytechcommerce';
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'display_title' => $this->getDisplayTitle(),
                    'display_description' => $this->getDisplayDescription()
                ]
            ]
        ];
    }

    protected function getDisplayTitle()
    {
        return $this->getConf('payment/paytechcommerce/title');
    }

    protected function getDisplayDescription()
    {
        return $this->getConf('payment/paytechcommerce/description');
    }
   protected $scopeConfig;
    public function getConf($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
