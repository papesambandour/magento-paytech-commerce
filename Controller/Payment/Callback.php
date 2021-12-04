<?php
namespace Paytech\PaytechCommerce\Controller\Payment;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Action\Context ;
use Magento\Sales\Model\Order;
use \Magento\Sales\Model\ResourceModel\Order as Order2;
class Callback extends \Magento\Framework\App\Action\Action
{

    protected $salesOrderFactory;
    protected $checkoutSession;
    protected $scopeConfig;
    protected $order;


    public function __construct(
        Context $context,
        Order $salesOrderFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        Order2 $order
    ) {
        $this->salesOrderFactory = $salesOrderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
        $this->order = $order;

        parent::__construct($context);
        die($this->ipn ($_REQUEST ));
       // die(json_encode($_REQUEST,JSON_PRETTY_PRINT));

    }

    public function execute()
    {
        die(json_encode($_REQUEST,JSON_PRETTY_PRINT));
       // exit;
      //return $this->ipn ($_REQUEST );
    }
    public function ipn ($data ): string
    {

        $type_event = @$data['type_event'];
        $custom_field = json_decode(@$data['custom_field'], true);
        $token = @$data['token'];
        $payment_method =@ $data['payment_method'];
        $api_key_sha256 = @$data['api_key_sha256'];
        $api_secret_sha256 = @$data['api_secret_sha256'];
        $my_api_key =$this->scopeConfig->getValue('payment/paytechcommerce/public_key');
        $my_api_secret = $this->scopeConfig->getValue('payment/paytechcommerce/secrete_key');

        if(hash('sha256', $my_api_secret) === $api_secret_sha256 && hash('sha256', $my_api_key) === $api_key_sha256)
        {
            $order_id= $custom_field['order_id'];
            $order = $this->salesOrderFactory->loadByIncrementId($order_id);
            if($order->getStatus() === 'pending'  ){
                $order->setStatus(Order::STATE_COMPLETE);
                // Create invoice for this order
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $invoice = $objectManager->create('Magento\Sales\Model\Service\InvoiceService')->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_OFFLINE);
                $invoice->register();
                // Save the invoice to the order
                $transaction = $objectManager->create('Magento\Framework\DB\Transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
                $transaction->save();
                $this->order->save($order);
                return "success";
            }else{
                return "no valide to success";
            }
        }
        else{
            return "failed";
        }
    }


}
