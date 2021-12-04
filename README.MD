https://magento.stackexchange.com/questions/293939/custom-payment-gateway-magento-2-3

https://www.magedelight.com/resources/steps-to-create-custom-payment-method-in-magento-2/

php bin/magento s:up
php bin/magento s:s:d -f
php bin/magento c:c


DISPLAY METHOD ON FTRONT

https://aureatelabs.com/magento-2/how-to-add-a-custom-payment-method-to-magento-2-checkout/

#REDIRECT EXTERNAL API

https://github.com/Adyen/adyen-magento2/blob/develop/view/frontend/web/js/view/payment/method-renderer/adyen-cc-method.js


#DISABLE CAPCHA
bin/magento security:recaptcha:disable-for-user-login
bin/magento security:recaptcha:disable-for-user-forgot-password
