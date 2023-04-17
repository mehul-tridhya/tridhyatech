<?php
namespace Tridhyatech\CouponCode\Block;

use Tridhyatech\CouponCode\Model\ConfigProvider;
use Magento\Framework\View\Element\Template\Context;

class Coupon extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Tridhyatech\CouponCode\Model\CustomConfigProvider
     */
    protected $configProvider;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCouponCodes()
    {
        return $this->configProvider->getCouponCodes();
    }
}