/**
* @author Tridhya Tech Team
* @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
* @package Tridhyatech_CouponCode
*/
var config = {
    map: {
        '*': {
            couponCode: 'Tridhyatech_CouponCode/js/coupon-codes',
        }
    },
    config: {
        mixins: {
            'Magento_SalesRule/js/view/payment/discount': {
                'Tridhyatech_CouponCode/js/view/payment/discount-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Tridhyatech_CouponCode/js/view/shipping-mixin': true
            }
        }
    }
};