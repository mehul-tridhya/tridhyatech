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