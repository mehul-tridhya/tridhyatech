/*global define*/
define(
    [
        "jquery",
        'ko',
        'mage/url',
        'Tridhyatech_CouponCode/js/model/couponcodes'
    ], //defile more js here
    function (
        $,
        ko,
        url,
        couponcodesmodel
    ) {
        'use strict';
        var mixins = {
            couponcodes: couponcodesmodel.getCouponCodes(),
            isCouponAvailable: couponcodesmodel.getIsCouponApplied(),
            setShippingInformation: function () { //Define new content for getTotal() function
                var _return = this._super(arguments); //Call default quote get Totals function.
                this.getCouponCode();
                return _return;
            },
            getCouponCode: function () {
                var self = this;
                var ajaxurl = url.build('couponcode/index/index');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    showLoader: true,
                    data: {},
                    success: function (response) {
                        self.couponcodes([]);
                        self.isCouponAvailable(false);
                        if (response.couponcodes && response.couponcodes.length) {
                            var codes = response.couponcodes;
                            self.couponcodes(codes.concat(self.couponcodes()));
                            self.isCouponAvailable(true);
                        }
                    }
                });
            }
        }
        return function (target) {
            //target = quote returned by Magento_Checkout/js/model/quote
            return target.extend(mixins); //Return quote again, it important if you don't return you can't use Magento_Checkout/js/model/quote by required.
        }
    });