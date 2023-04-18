<?php

namespace Tridhyatech\CouponCode\Controller\Index;

use Tridhyatech\CouponCode\Model\ConfigProvider;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Zend_Json;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_configProvider;
    protected $_resultJsonFactory;
    protected $_scopeConfig;

    const XML_PATH_COUPON_CODE = 'coupon_code_config/coupon_list/is_active';
    const SCOPE_STORE = ScopeInterface::SCOPE_STORE;

    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        JsonFactory $resultJsonFactory,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->_configProvider = $configProvider;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_scopeConfig = $scopeConfig;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->_resultJsonFactory->create();
        $isModuleEnable = $this->isModuleEnable();
        return $resultJson->setData(['couponcodes' => $this->_configProvider->getCouponCodes(),'is_module_enable' => $isModuleEnable]);
    }

    public function isModuleEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_COUPON_CODE, self::SCOPE_STORE) ? true : false ;
    }
}
