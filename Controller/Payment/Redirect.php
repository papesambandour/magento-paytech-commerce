<?php
namespace Paytech\PaytechCommerce\Controller\Payment;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\Order;
use Paytech\PaytechCommerce\Model\Payment\PayTech;

class Redirect extends \Magento\Customer\Controller\AbstractAccount
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
    }

    public function execute()
    {
        $success= $_GET['success'];
        $idOrder= $_GET['_order_id_'];
        if($success){
            $this->_redirect('checkout/onepage/success');
        }else{

            $order = $this->salesOrderFactory->load($idOrder);
            $order->cancel()->setState(\Magento\Sales\Model\Order::STATE_CANCELED, true, 'Annulation Paiement')->save();
            $this->_redirect('checkout/onepage/failure');
        }
    }


}
