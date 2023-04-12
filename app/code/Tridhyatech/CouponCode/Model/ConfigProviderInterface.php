<?php

namespace Tridhyatech\CouponCode\Model;

/**
 * Interface ConfigProviderInterface
 * @api
 */
interface ConfigProviderInterface
{

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getCouponCodes();
}