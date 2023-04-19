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
        isModuleEnable = ko.observable(false),
        couponListType = ko.observable(1),
        isCouponChanged = ko.observable(false);
    return {
        couponCodes: couponCodes,

        /**
         * @return {*}
         */
        getCouponCodes: function () {
            return couponCodes;
        },

        getIsModuleEnable: function () {
            return isModuleEnable;
        },

        setIsModuleEnable: function (isModuleEnableValue) {
            isModuleEnable(isModuleEnableValue);
        },

        getIsCouponChanged: function () {
            return isCouponChanged;
        },

        setIsCouponChanged: function (isCouponChangedValue) {
            isCouponChanged(isCouponChangedValue);
        },

        getCouponListType: function () {
            return couponListType;
        },

        setCouponListType: function (couponListTypeValue) {
            couponListType(couponListTypeValue);
        },
        
        setCouponCodes: function (coupons) {
            couponCodes(coupons);
        },
        getCouponCode: function () {
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
                    if (response.coupon_list_type) {
                        self.setCouponListType(parseInt(response.coupon_list_type));
                    }
                    if (response.couponcodes) {
                        self.setCouponCodes(codes);
                        var codes = response.couponcodes;
                        self.setCouponCodes(codes);
                    }
                }
            });
        }
    };
});
