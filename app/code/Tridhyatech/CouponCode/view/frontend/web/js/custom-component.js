define([
    'jquery',
    'uiComponent',
    'ko',
    'domReady',
    'mage/storage'
], function ($, Component, ko, domReady, storage) {
    'use strict';
    var customData = window.customConfig;
    return Component.extend({
        defaults: {
            // template: 'Tridhyatech_CouponCode/knockout-test'
        },
        initialize: function () {
            this._super();
        }
    });
}
);