/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/url',
], function ($, url) {
    'use strict';
    return {
        applyCouponCode: function(obj){
            $('#discount-coupon-form').find('#coupon_code').val($(obj).val());
            $('#discount-coupon-form').find('.apply').trigger('click');
        },
    }
});
