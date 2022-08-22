define([
    'ko',
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/action/redirect-on-success'
], function (ko, Component, redirectOnSuccessAction) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Perspective_LiqPayPayment/payment/liqpay'
        },

        afterPlaceOrder: function () {
            redirectOnSuccessAction.redirectUrl = 'checkout/liqpay';
            this.redirectAfterPlaceOrder = true;
        },

        /**
         * Get value of instruction field.
         * @returns {String}
         */
        getInstructions: function () {
            return window.checkoutConfig.payment.instructions[this.item.method];
        }
    });
});
