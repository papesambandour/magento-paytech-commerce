<?php
namespace Paytech\PaytechCommerce\Controller\Payment;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\Order;
use Paytech\PaytechCommerce\Model\Payment\PayTech;
use \Magento\Sales\Model\ResourceModel\Order as Order2;

class Redirect extends \Magento\Framework\App\Action\Action
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
    }

    public function execute()
    {
        $success= $_GET['success'];
        $idOrder= $_GET['_order_id_'];
        if($success){
            $this->_redirect('checkout/onepage/success');
        }else{
            $order = $this->salesOrderFactory->loadByIncrementId($idOrder);
            $order->cancel()->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
            $this->order->save($order);
            $this->_redirect('checkout/onepage/failure');
        }
        //localhost/paytechcommerce/payment/redirect?success=0&_order_id_=000000009
    }


}
