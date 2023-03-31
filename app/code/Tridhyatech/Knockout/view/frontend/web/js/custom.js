define([
    "jquery",
    "jquery/ui",
], function ($) {
    return {
        changeQty: function (obj,classId) {
            console.log(obj);
            var obj = $(obj);
            var currentQty = obj.siblings('.'+classId).val();
            if (obj.hasClass('more')) {
                var newAdd = parseInt(currentQty) + parseInt(1);
                obj.siblings('.'+classId).val(newAdd);
                obj.siblings('.'+classId).attr('data-item-qty', newAdd);
                obj.parent().next('.update-cart-item').show();
            } else {
                if (parseInt(currentQty) > 1) {
                    var newAdd = parseInt(currentQty) - parseInt(1);
                    obj.siblings('.'+classId).val(newAdd);
                    obj.siblings('.'+classId).attr('data-item-qty', newAdd);
                    obj.parent().next('.update-cart-item').show();
                }
            }
        }
    }
});