<?php
namespace Paytech\PaytechCommerce\Controller\Payment;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\Order;
use Paytech\PaytechCommerce\Model\Payment\PayTech;

class Request extends \Magento\Customer\Controller\AbstractAccount
{

    protected $salesOrderFactory;
    protected $checkoutSession;
    protected $scopeConfig;


    public function __construct(
        Context $context,
        Order $salesOrderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->salesOrderFactory = $salesOrderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
        $order = $this->salesOrderFactory->loadByIncrementId($this->checkoutSession->getLastRealOrder()->getIncrementId());
        $orderDetails = $order->getData();
        $response = $this->requestPayment($orderDetails,$order->getOrderCurrencyCode());
        die(json_encode($response,JSON_PRETTY_PRINT));
        //$this->_redirect($response['redirect_url']);
    }

    public function execute()
    {

    }
    public function requestPayment($orderDetails,$currency): array
    {

        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $ipn = str_replace("http://","https://",$storeManager->getStore()->getBaseUrl());

            $id = $orderDetails['increment_id'];
            $public_key= $this->scopeConfig->getValue('payment/paytechcommerce/public_key');
            $secrete_key= $this->scopeConfig->getValue('payment/paytechcommerce/secrete_key');
            $environement_api= $this->scopeConfig->getValue('payment/paytechcommerce/environement_api');

            return (new PayTech($public_key, $secrete_key))->setQuery([
                'item_name' =>"Paiement via Magento de {$orderDetails['grand_total']} FRCFA",
                'item_price' => floatval($orderDetails['grand_total']),
                'command_name' => "Paiement facture Paytick de {$orderDetails['grand_total']} FRCFA",
            ])->setCustomeField([
                'order_id' => $id
            ])
                ->setTestMode($environement_api=='test')
                ->setCurrency($currency)
                ->setRefCommand($id . '|'. uniqid())
                ->setNotificationUrl([
                    'ipn_url' => $ipn . 'paytechcommerce/payment/callback', //only https
                    'success_url' => $storeManager->getStore()->getBaseUrl() . 'paytechcommerce/payment/redirect?success=1&_order_id_=' . $id,
                    'cancel_url' => $storeManager->getStore()->getBaseUrl() .  'paytechcommerce/payment/redirect?success=0&_order_id_=' . $id,
                ])->send();

        } catch (\Exception $e) {
            return [$e->getMessage()];
        }
    }

}
