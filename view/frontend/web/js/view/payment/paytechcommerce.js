define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'paytechcommerce',
                component: 'Paytech_PaytechCommerce/js/view/payment/method-renderer/paytechcommerce-method'
            }
        );
        return Component.extend({});
    }
);
