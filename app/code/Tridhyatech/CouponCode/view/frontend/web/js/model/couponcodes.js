/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * Coupon model.
 */
define([
    'ko',
    'domReady!',
    'mage/url',
    'jQuery'
], function (ko,domReady,url,$) {
    'use strict';

    var couponCodes = ko.observableArray(null),
        isCouponApplied = ko.observable(null),
        isModuleEnable = ko.observable(false);
    return {
        couponCodes: couponCodes,
        isCouponApplied: isCouponApplied,

        /**
         * @return {*}
         */
        getCouponCodes: function () {
            return couponCodes;
        },

        /**
         * @return {Boolean}
         */
        getIsCouponApplied: function () {
            return isCouponApplied;
        },

        getIsModuleEnable: function () {
            return isModuleEnable;
        },

        setIsModuleEnable: function (isModuleEnableValue) {
            isModuleEnable(isModuleEnableValue);
        },
        
        setCouponCodes: function (coupons) {
            couponCodes(coupons);
        },

        /**
         * @param {Boolean} isCouponAppliedValue
         */
        setIsCouponApplied: function (isCouponAppliedValue) {
            isCouponApplied(isCouponAppliedValue);
        },
        getCouponCode: function (flag = false) {
            var self = this;
            var ajaxurl = url.build('couponcode/index/index');
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                showLoader: true,
                data: {},
                success: function (response) {
                    self.setCouponCodes([]);
                    if (response.is_module_enable) {
                        var isEnable = response.is_module_enable;
                        self.setIsModuleEnable(isEnable);
                    }
                    if (response.couponcodes && response.couponcodes.length) {
                        var codes = response.couponcodes;
                        var isEnable = response.is_module_enable;
                        self.setCouponCodes(codes);
                        self.setIsModuleEnable(isEnable);
                        if(flag){
                            self.setIsCouponApplied(true);
                        }
                    }
                }
            });
        }
    };
});
