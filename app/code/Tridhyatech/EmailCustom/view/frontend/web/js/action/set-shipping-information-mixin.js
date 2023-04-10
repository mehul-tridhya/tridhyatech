/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            var deliveryDate = $('[name="delivery_date"]').val();
            var deliveryNote = $('[name="delivery_note"]').val();
            if (shippingAddress.extensionAttributes) {
                shippingAddress['extensionAttributes']['delivery_date'] = deliveryDate;
                shippingAddress['extensionAttributes']['delivery_note'] = deliveryNote;
            }else{
                shippingAddress['extensionAttributes'] = {
                    'delivery_date' : deliveryDate,
                    'delivery_note': deliveryNote
                }
            }
            // you can write here your code according to your requirement
            return originalAction(); // it is returning the flow to original action
        });
    };
});