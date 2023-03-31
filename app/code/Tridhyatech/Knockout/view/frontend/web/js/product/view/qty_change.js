define([
    'ko',
    'uiComponent'
], function (ko, Component) {
    'use strict';

    return Component.extend({

        initialize: function () {
            //initialize parent Component
            this._super();
        },

        decreaseQty: function (data,event) {
            var obj = jQuery(event.target).parent().find('input');
            var newQty = parseInt(jQuery(obj).val()) - 1;
            if (newQty < 1) {
                newQty = 1;
            }
            jQuery(obj).val(newQty);
            jQuery(obj).parent().next('.update-cart-item').show();
        },

        increaseQty: function (data,event) {
            var obj = jQuery(event.target).parent().find('input');
            var newQty = parseInt(jQuery(obj).val()) + 1;
            jQuery(obj).val(newQty);
            jQuery(obj).parent().next('.update-cart-item').show();
        }

    });
});