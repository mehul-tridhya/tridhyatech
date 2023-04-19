<?php
namespace Tridhyatech\CouponCode\Block;

use Tridhyatech\CouponCode\Model\ConfigProvider;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Coupon extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Tridhyatech\CouponCode\Model\CustomConfigProvider
     */
    protected $configProvider;
    protected $_scopeConfig;

    const XML_PATH_COUPON_CODE = 'coupon_code_config/coupon_list/is_active';
    const XML_PATH_COUPON_CODE_LIST = 'coupon_code_config/coupon_list/type';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->_scopeConfig = $scopeConfig;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getCouponCodes()
    {
        return $this->configProvider->getCouponCodesWithDetails();
    }

    public function isModuleEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_COUPON_CODE, self::SCOPE_STORE);
    }

    public function getCouponListType()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_COUPON_CODE_LIST, self::SCOPE_STORE);
    }

    public function getTitle()
    {
        return $this->getCouponListType() == 1 ? 'All Coupons' : 'Available Coupons';
    }   
}