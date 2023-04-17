<?php

namespace Tridhyatech\CouponCode\Controller\Index;

use Tridhyatech\CouponCode\Model\ConfigProvider;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Zend_Json;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_configProvider;
    protected $_resultJsonFactory;

    public function __construct(
        Context $context,
        ConfigProvider $configProvider,
        JsonFactory $resultJsonFactory
    ) {
        $this->_configProvider = $configProvider;
        $this->_resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->_resultJsonFactory->create();
        return $resultJson->setData(['couponcodes' => $this->_configProvider->getCouponCodes()]);
    }
}
