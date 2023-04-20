<?php
/**
* @author Tridhya Tech Team
* @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
* @package Tridhyatech_CouponCode
*/
namespace Tridhyatech\CouponCode\Block;

use Tridhyatech\CouponCode\Model\ConfigProvider;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Coupon extends \Magento\Framework\View\Element\Template
{

    protected $_configProvider;
    protected $_scopeConfig;
    
    /**
     *
     * @param Context $context
     * @param ConfigProvider $configProvider
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_configProvider = $configProvider;
        $this->_scopeConfig = $scopeConfig;
    }
    
    /**
     * get coupon codes
     *
     * @return void
     */
    public function getCouponCodes()
    {
        return $this->_configProvider->getCouponCodesWithDetails();
    }

    /**
     * get is module enable
     *
     * @return boolean
     */
    public function isModuleEnable()
    {
        return $this->_configProvider->isModuleEnable();
    }

    /**
     * get coupon list type
     *
     * @return boolean
     */
    public function getCouponListType()
    {
        return $this->_configProvider->getCouponListType();
    }

    /**
     * get title for modal based on coupon list type
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getCouponListType() == 1 ? $this->getAllCouponTitle() : $this->getCartWiseCouponTitle();
    }

    /**
     * get Button Title
     *
     * @return string
     */
    public function getButtonTitle()
    {
        return $this->_configProvider->getButtonTitle();
    }

    /**
     * get Title For Available coupons
     *
     * @return string
     */
    public function getAvailableCouponTitle()
    {
        return $this->_configProvider->getAvailableCouponTitle();
    }

    /**
     * get Title For Unavailable coupons
     *
     * @return string
     */
    public function getUnavailableCouponTitle()
    {
        return $this->_configProvider->getUnavailableCouponTitle();
    }

    /**
     * get Title For All Coupons
     *
     * @return string
     */
    public function getAllCouponTitle()
    {
        return $this->_configProvider->getAllCouponTitle();
    }

    /**
     * get Title For Cart Wise Availabe Coupons
     *
     * @return string
     */
    public function getCartWiseCouponTitle()
    {
        return $this->_configProvider->getCartWiseCouponTitle();
    }
}