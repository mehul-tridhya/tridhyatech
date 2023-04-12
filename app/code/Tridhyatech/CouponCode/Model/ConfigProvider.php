<?php
namespace Tridhyatech\CouponCode\Model;
use Magento\SalesRule\Model\CouponFactory;

class ConfigProvider implements ConfigProviderInterface
{
    protected $_couponFactory;

    public function __construct(
        CouponFactory $couponFactory,
    ) {
        $this->_couponFactory = $couponFactory;
    }

    public function getCouponCodes()
    {
        return $this->_couponFactory->create()
        ->getCollection()->getColumnValues('code');
    }
}