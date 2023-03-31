define([
    "jquery"
],
    function ($) {
        "use strict";
        function changeQty(obj,data,event) {
            console.log(123);
            var obj = $(obj);
            var currentQty = obj.siblings('.cart-item-qty').val();
            if (obj.hasClass('more')) {
                var newAdd = parseInt(currentQty) + parseInt(1);
                obj.siblings('.cart-item-qty').val(newAdd);
                obj.siblings('.cart-item-qty').attr('data-item-qty', newAdd);
                $('.update-cart-item').show();
            } else {
                if (parseInt(currentQty) > 1) {
                    var newAdd = parseInt(currentQty) - parseInt(1);
                    obj.siblings('.cart-item-qty').val(newAdd);
                    obj.siblings('.cart-item-qty').attr('data-item-qty', newAdd);
                    $('.update-cart-item').show();
                }
            }
        }
    });