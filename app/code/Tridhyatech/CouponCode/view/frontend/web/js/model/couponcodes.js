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
        isCouponApplied = ko.observable(null);
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
                    if (response.couponcodes && response.couponcodes.length) {
                        var codes = response.couponcodes;
                        self.setCouponCodes(codes);
                        if(flag){
                            self.setIsCouponApplied(true);
                        }
                    }
                }
            });
        }
    };
});
