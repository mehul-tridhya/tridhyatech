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
    'domReady',
    'Magento_Ui/js/modal/modal'
], function ($, ko, coupon, url,couponcodesmodel,domReady,modal) {
    'use strict';
    domReady(function() {
        couponcodesmodel.getCouponCode();
    });
    var couponCode = coupon.getCouponCode();
    var couponCodes = couponcodesmodel.getCouponCodes();
    var isModuleEnable = couponcodesmodel.getIsModuleEnable();
    var couponListType = couponcodesmodel.getCouponListType();
    var isCouponChanged = couponcodesmodel.getIsCouponChanged();
    isCouponChanged.subscribe(function(newValue) {
        if(newValue){
            couponcodesmodel.getCouponCode();
            couponcodesmodel.setIsCouponChanged(false);
        };
    });
    return function (targetModule) {
        return targetModule.extend({
            defaults: {
                template: 'Tridhyatech_CouponCode/payment/discount'
            },
            couponcodes: couponCodes,
            isModuleEnable : isModuleEnable,
            couponListType : couponListType,
            initialize: function () {
                var self = this;
                this._super();
            },
            applyCuponCode: function (couponvalue,elementId) {
                couponCode(couponvalue);
                this.apply();
                $(elementId).modal("closeModal");
            },
            openCouponModal: function (elementId) {
                var title = this.couponListType() == 1 ? 'All Coupons' : 'Available Coupons'; 
                var options = {
                    type: 'slide',
                    responsive: true,
                    innerScroll: true,
                    title: title,
                    modalClass: 'coupon-list-modal',
                    buttons: []
                };
                var popup = modal(options, $(elementId));
                $(elementId).modal("openModal");
            }
        })
    }
});
