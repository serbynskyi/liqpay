define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'liqpay',
            component: 'Perspective_LiqPayPayment/js/view/payment/method-renderer/liqpay-method'
        }
    );
    return Component.extend({});
});
