<?php
/**
 * @author Tridhya Tech Team
 * @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
 * @package Tridhyatech_CouponList
 */
namespace Tridhyatech\CouponList\Model;

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
