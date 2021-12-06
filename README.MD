#**Paytech Plugin Magento**

###Plugin de paiement paytech pour magento

####1-Insatallation
_composer require paytech/paytech-commerce_


####2-Activer le module
_php bin/magento module:enable Paytech_PaytechCommerce --clear-static-content_

_php bin/magento setup:di:compile_

_php bin/magento setup:upgrade_
