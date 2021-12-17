
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
function sendNormalPaymentRequest() {
    (new PayTech({
    })).withOption({
        requestTokenUrl           :   '/paytechcommerce/payment/request',
        method              :   'POST',
        prensentationMode   :   PayTech.OPEN_IN_POPUP,
        didPopupClosed: function (is_completed, success_url, cancel_url) {
            // window.location.href = is_completed === true ? success_url  : cancel_url;
        },
        willGetToken        :   function () {
            console.log("Je me prepare a obtenir un token");
            //selector.prop('disabled', true);
        },
        didGetToken         : function (token, redirectUrl) {
            console.log("Mon token est : " +  token  + ' et url est ' + redirectUrl );
        },
        didReceiveError: function (error) {
            alert('erreur inconnu', error.toString());
        },
        didReceiveNonSuccessResponse: function (jsonResponse) {
            console.log('non success response ',jsonResponse);
            alert(jsonResponse.errors);
        }
    }).send();

}
// function init(){
//     $.getScript( "ajax/test.js", function( data, textStatus, jqxhr ) {
//         console.log( data ); // Data returned
//         console.log( textStatus ); // Success
//         console.log( jqxhr.status ); // 200
//         console.log( "Load was performed." );
//     });
// }
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/place-order',
        'Magento_Checkout/js/action/select-payment-method',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/checkout-data',
        'Magento_Checkout/js/model/payment/additional-validators',
        'mage/url',
        'https://paytech.sn/cdn/paytech.min.js'
    ],
    function (
        Component,
        placeOrderAction,
        selectPaymentMethodAction,
        customerData,
        checkoutData,
        additionalValidators,
        url) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Paytech_PaytechCommerce/payment/paytechcommerce'
            },

            placeOrder: function (data, event) {
                if (event) {
                    event.preventDefault();
                }
                var self = this,
                    placeOrder;

                this.isPlaceOrderActionAllowed(false);

                placeOrder = placeOrderAction(this.getData(), false, this.messageContainer);

                jQuery.when(placeOrder).then(function () {
                    self.isPlaceOrderActionAllowed(true);
                }).done(this.afterPlaceOrder.bind(this));
                return true;

            },

            afterPlaceOrder: function () {
                console.log('orderplaced');
                // window.location.replace(url.build('/paytechcommerce/payment/request'));
                sendNormalPaymentRequest();
                console.log('redirected');
            },

            getMailingAddress: function() {
                return window.checkoutConfig.payment.checkmo.mailingAddress;
            },

            getDisplayTitle: function() {
                return window.checkoutConfig.payment.paytechcommerce.title;
            },

            getDisplayDescription: function() {
                return window.checkoutConfig.payment.paytechcommerce.description;
            }
        });
    }
);

