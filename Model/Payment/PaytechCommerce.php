<?php
namespace Paytech\PaytechCommerce\Model\Payment;

class PaytechCommerce extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "paytechcommerce";
    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
