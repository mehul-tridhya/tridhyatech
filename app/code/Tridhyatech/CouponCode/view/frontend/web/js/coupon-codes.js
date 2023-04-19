/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/modal'
], function ($, url,modal) {
    'use strict';
    return {
        applyCouponCode: function (code) {
            $('#discount-coupon-form').find('#coupon_code').val(code);
            $('#discount-coupon-form').find('.apply').trigger('click');
        },
        openCouponModal: function (title,elementId){
            var options = {
                type: 'slide',
                responsive: true,
                innerScroll: true,
                modalClass: 'coupon-list-modal',
                title: title,
                buttons: []
            };
            var popup = modal(options, $(elementId));
            $(elementId).modal("openModal");
        }
    }
});
