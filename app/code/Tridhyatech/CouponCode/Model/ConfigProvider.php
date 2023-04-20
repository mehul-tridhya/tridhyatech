<?php

/**
 * @author Tridhya Tech Team
 * @copyright Copyright (c) 2023 Tridhya Tech Ltd (https://www.tridhyatech.com)
 * @package Tridhyatech_CouponCode
 */

namespace Tridhyatech\CouponCode\Model;

use Magento\SalesRule\Model\CouponFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Cart;
use Magento\SalesRule\Model\Utility;
use Magento\SalesRule\Model\RuleFactory;
use Magento\SalesRule\Model\Quote\ChildrenValidationLocator;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider implements ConfigProviderInterface
{
    protected $_couponFactory;
    protected $_logger;
    protected $_ruleRepository;
    protected $_cart;
    protected $_validatorUtility;
    protected $_childrenValidationLocator;
    protected $_customerSession;
    protected $_storeManager;
    protected $_scopeConfig;

    const XML_PATH_COUPON_CODE = 'coupon_code_config/coupon_list/is_active';
    const XML_PATH_BUTTON_TITLE = 'coupon_code_config/coupon_list/button_title';
    const XML_PATH_AVAILABLE_COUPON_TITLE = 'coupon_code_config/coupon_list/availabe_coupon_title';
    const XML_PATH_UNAVAILABLE_COUPON_TITLE = 'coupon_code_config/coupon_list/unavailable_coupon_title';
    const XML_PATH_ALL_COUPON_TITLE = 'coupon_code_config/coupon_list/all_coupon_title';
    const XML_PATH_CART_WISE_COUPON_TITLE = 'coupon_code_config/coupon_list/cart_wise_coupon_title';
    const XML_PATH_COUPON_CODE_LIST = 'coupon_code_config/coupon_list/type';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    /**
     *
     * @param CouponFactory $couponFactory
     * @param LoggerInterface $logger
     * @param RuleFactory $ruleRepository
     * @param Cart $cart
     * @param Utility $utility
     * @param ChildrenValidationLocator $childrenValidationLocator
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CouponFactory $couponFactory,
        LoggerInterface $logger,
        RuleFactory $ruleRepository,
        Cart $cart,
        Utility $utility,
        ChildrenValidationLocator $childrenValidationLocator,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->_couponFactory = $couponFactory;
        $this->_logger = $logger;
        $this->_ruleRepository = $ruleRepository;
        $this->_cart = $cart;
        $this->_validatorUtility = $utility;
        $this->_childrenValidationLocator = $childrenValidationLocator;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * get coupon list type
     *
     * @return void
     */
    public function getCouponListType()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_COUPON_CODE_LIST, self::SCOPE_STORE);
    }

    /**
     * get is module enable
     *
     * @return boolean
     */
    public function isModuleEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_COUPON_CODE, self::SCOPE_STORE);
    }

    /**
     * get title for modal based on coupon list type
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getCouponListType() == 1 ? 'All Coupons' : 'Available Coupons';
    }

    /**
     * get Button Title
     *
     * @return string
     */
    public function getButtonTitle()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_BUTTON_TITLE, self::SCOPE_STORE);
    }

    /**
     * get Title For Available coupons
     *
     * @return string
     */
    public function getAvailableCouponTitle()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_AVAILABLE_COUPON_TITLE, self::SCOPE_STORE);
    }

    /**
     * get Title For Unavailable coupons
     *
     * @return string
     */
    public function getUnavailableCouponTitle()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_UNAVAILABLE_COUPON_TITLE, self::SCOPE_STORE);
    }

    /**
     * get Title For All Coupons
     *
     * @return string
     */
    public function getAllCouponTitle()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_ALL_COUPON_TITLE, self::SCOPE_STORE);
    }

    /**
     * get Title For Cart Wise Availabe Coupons
     *
     * @return string
     */
    public function getCartWiseCouponTitle()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CART_WISE_COUPON_TITLE, self::SCOPE_STORE);
    }

    /**
     * get available and unavailable coupon codes array
     *
     * @return array
     */
    public function getCouponCodesWithDetails()
    {
        $validCouponCodes = $allCoupons = $invalidCouponCodes = [];
        $coupons = $this->_couponFactory->create()->getCollection()->getData();
        $customer = $this->_customerSession->getCustomer();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        if ($this->_cart->getItemsCount() != 0) {
            $allItems = $this->_cart->getQuote()->getAllItems();
            foreach ($coupons as $coupon) {
                $isValidCoupon = true;
                $salesRule = null;
                try {
                    $salesRule = $this->_ruleRepository->create()->load($coupon['rule_id']);
                    $allCoupons[] = $salesRule->getCouponCode();
                    if (!$salesRule->getIsActive()) {
                        $isValidCoupon = false;
                    }
                    $today = strtotime(date("Y-m-d"));
                    $startDay = $salesRule->getFromDate();
                    $expirationDay = $salesRule->getToDate();

                    $numUses = $coupon['times_used'];
                    $maxUses = $coupon['usage_limit'];
                    // Discount code is expired
                    if ($expirationDay && strtotime($expirationDay) < $today) {
                        $isValidCoupon = false;
                    }
                    // Discount hasn't started yet
                    else if ($startDay && strtotime($startDay) > $today) {
                        $isValidCoupon = false;
                    }
                    // Coupon has already been fully consumed
                    else if ($maxUses && $numUses >= $maxUses) {
                        $isValidCoupon = false;
                    }
                    if ($websiteId && !in_array($websiteId, $salesRule->getWebsiteIds())) {
                        $isValidCoupon = false;
                    }
                    if (count($salesRule->getCustomerGroupIds()) == 1 && in_array(0, $salesRule->getCustomerGroupIds()) && !$customer->getId()) {
                        $isValidCoupon = false;
                    }
                    if (count($salesRule->getCustomerGroupIds()) == 1 && in_array(0, $salesRule->getCustomerGroupIds()) && $customer->getId()) {
                        $isValidCoupon = false;
                    }
                    if (!in_array(0, $salesRule->getCustomerGroupIds()) && $customer->getId() && !in_array($customer->getGroupId(), $salesRule->getCustomerGroupIds())) {
                        $isValidCoupon = false;
                    }
                    foreach ($allItems as $item) {
                        $address = $item->getAddress();
                        $isValid = $this->_validatorUtility->canProcessRule($salesRule, $address);
                        if (!$isValid) {
                            $isValidCoupon = false;
                        }
                        if (!$salesRule->getActions()->validate($item)) {
                            if (!$this->_childrenValidationLocator->isChildrenValidationRequired($item)) {
                                $isValidCoupon = false;
                            }
                            $childItems = $item->getChildren();
                            $isContinue = true;
                            if (!empty($childItems)) {
                                foreach ($childItems as $childItem) {
                                    if ($salesRule->getActions()->validate($childItem)) {
                                        $isContinue = false;
                                    }
                                }
                            }
                            if ($isContinue) {
                                $isValidCoupon = false;
                            }
                        }
                        $rule = [
                            'coupon_code' => $salesRule->getCouponCode(),
                            'name' => $salesRule->getName(),
                            'description' => $salesRule->getDescription(),
                        ];
                        if ($isValidCoupon && !in_array($rule, $validCouponCodes)) {
                            $validCouponCodes[] = $rule;
                        } elseif (!in_array($rule, $invalidCouponCodes)) {
                            $invalidCouponCodes[] = $rule;
                        }
                    }
                } catch (Exception $exception) {
                    $this->_logger->error($exception->getMessage());
                }
            }
        }
        return [
            'valid_coupons' => $validCouponCodes,
            'invalid_coupons' => $invalidCouponCodes
        ];
    }
}
