<?php

namespace Tridhyatech\CouponCode\Model;

use Magento\SalesRule\Model\CouponFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\SalesRule\Model\Utility;
use Magento\SalesRule\Model\RuleFactory;
use Magento\SalesRule\Model\Quote\ChildrenValidationLocator;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

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

    public function __construct(
        CouponFactory $couponFactory,
        LoggerInterface $logger,
        RuleFactory $ruleRepository,
        Cart $cart,
        Utility $utility,
        ChildrenValidationLocator $childrenValidationLocator,
        Session $customerSession,
        StoreManagerInterface $storeManager
    ) {
        $this->_couponFactory = $couponFactory;
        $this->_logger = $logger;
        $this->_ruleRepository = $ruleRepository;
        $this->_cart = $cart;
        $this->_validatorUtility = $utility;
        $this->_childrenValidationLocator = $childrenValidationLocator;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    public function getCouponCodes()
    {
        $validCouponCodes = [];
        $appliedRuleIds = [];
        $coupons = $this->_couponFactory->create()->getCollection()->getData();
        $customer = $this->_customerSession->getCustomer();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        if ($this->_cart->getItemsCount() != 0) {
            $allItems = $this->_cart->getQuote()->getAllItems();
            foreach ($coupons as $coupon) {
                $salesRule = null;
                try {
                    $salesRule = $this->_ruleRepository->create()->load($coupon['rule_id']);
                    if (!$salesRule->getIsActive()) {
                        continue;
                    }
                    $today = strtotime(date("Y-m-d"));
                    $startDay = $salesRule->getFromDate();
                    $expirationDay = $salesRule->getToDate();

                    $numUses = $coupon['times_used'];
                    $maxUses = $coupon['usage_limit'];
                    // Discount code is expired
                    if ($expirationDay && strtotime($expirationDay) < $today) {
                        continue;
                    }
                    // Discount hasn't started yet
                    else if ($startDay && strtotime($startDay) > $today) {
                        continue;
                    }
                    // Coupon has already been fully consumed
                    else if ($maxUses && $numUses >= $maxUses) {
                        continue;
                    }
                    if ($websiteId && !in_array($websiteId, $salesRule->getWebsiteIds())) {
                        continue;
                    }
                    if (count($salesRule->getCustomerGroupIds()) == 1 && in_array(0, $salesRule->getCustomerGroupIds()) && !$customer->getId()) {
                        continue;
                    }
                    if (count($salesRule->getCustomerGroupIds()) == 1 && in_array(0, $salesRule->getCustomerGroupIds()) && $customer->getId()) {
                        continue;
                    }
                    if (!in_array(0, $salesRule->getCustomerGroupIds()) && $customer->getId() && !in_array($customer->getGroupId(), $salesRule->getCustomerGroupIds())) {
                        continue;
                    }
                    foreach ($allItems as $item) {
                        $address = $item->getAddress();
                        $isValid = $this->_validatorUtility->canProcessRule($salesRule, $address);
                        if (!$isValid) {
                            continue;
                        }
                        if (!$salesRule->getActions()->validate($item)) {
                            if (!$this->_childrenValidationLocator->isChildrenValidationRequired($item)) {
                                continue;
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
                                continue;
                            }
                        }
                        if (!in_array($coupon['code'], $validCouponCodes)) {
                            $validCouponCodes[] = $coupon['code'];
                        }
                        if (!in_array($salesRule->getId(), $appliedRuleIds)) {
                            $appliedRuleIds[] = $salesRule->getId();
                        }
                    }
                } catch (Exception $exception) {
                    $this->_logger->error($exception->getMessage());
                }
            }
        }
        return $validCouponCodes;
    }
}
