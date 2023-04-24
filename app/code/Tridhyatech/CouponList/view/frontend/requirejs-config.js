/**
* @author Tridhya Tech Team
* @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
* @package Tridhyatech_CouponList
*/
var config = {
    map: {
        '*': {
            couponCode: 'Tridhyatech_CouponList/js/coupon-codes',
        }
    },
    config: {
        mixins: {
            'Magento_SalesRule/js/view/payment/discount': {
                'Tridhyatech_CouponList/js/view/payment/discount-mixin': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Tridhyatech_CouponList/js/view/shipping-mixin': true
            }
        }
    }
};