<?php
/**
 * @author Tridhya Tech Team
 * @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
 * @package Tridhyatech_CouponCode
 */
namespace Tridhyatech\CouponCode\Model;

/**
 * Interface ConfigProviderInterface
 */
interface ConfigProviderInterface
{

    /**
     * Retrieve array of all coupon codes
     *
     * @return array
     */
    public function getCouponCodesWithDetails();
}
