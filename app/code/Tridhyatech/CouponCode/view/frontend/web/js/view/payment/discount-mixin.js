/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/url'
], function ($,url) {
    'use strict';
    var customData = window.customConfig;
    return function (targetModule) {
        return targetModule.extend({
            defaults: {
                template: 'Tridhyatech_CouponCode/payment/discount'
            },
            customFunction: function () {
                for (let i = 0; i < customData.length; i++) {
                    var apiUrl = url.build("rest/default/V1/carts/mine/validcoupons/" + customData[i]);
                    $.ajax({
                        type: "POST",
                        url: apiUrl,
                        data: {},
                        success: function (response) {
                            console.log(customData[i]);
                            validCouponCodes.push(customData[i]);
                        },
                        dataType: 'json',
                        contentType: 'application/json',
                    });
                }
            },
        })
    }
});
