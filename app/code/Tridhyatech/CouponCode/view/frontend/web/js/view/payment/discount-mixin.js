/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'ko',
    'Magento_SalesRule/js/model/coupon',
    'mage/url',
    'Tridhyatech_CouponCode/js/model/couponcodes',
    'domReady'
], function ($, ko, coupon, url,couponcodesmodel,domReady) {
    'use strict';
    domReady(function() {
        couponcodesmodel.getCouponCode(true);
    });
    var couponCode = coupon.getCouponCode();
    var couponCodes = couponcodesmodel.getCouponCodes();
    var isCouponAvailable = couponcodesmodel.getIsCouponApplied();
    var isModuleEnable = couponcodesmodel.getIsModuleEnable();
    isCouponAvailable.subscribe(function(newValue) {
        if(newValue){
            couponcodesmodel.getCouponCode();
        };
    });
    return function (targetModule) {
        return targetModule.extend({
            defaults: {
                template: 'Tridhyatech_CouponCode/payment/discount'
            },
            couponcodes: couponCodes,
            isCouponAvailable: isCouponAvailable,
            isModuleEnable : isModuleEnable,
            initialize: function () {
                var self = this;
                this._super();
            },
            customFunction: function (data, event) {
                event.stopPropagation();
                couponCode(data);
                this.apply();
            }
        })
    }
});
