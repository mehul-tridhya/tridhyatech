<?php

namespace Tridhyatech\CouponCode\Controller\Index;

use Magento\SalesRule\Model\CouponFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_request;
    protected $_coreRegistry;
    protected $_couponFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Http $request,
        Registry $coreRegistry,
        CouponFactory $couponFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_request = $request;
        $this->_coreRegistry = $coreRegistry;
        $this->_couponFactory = $couponFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->_couponFactory->create()
        ->getCollection()->getData();
        return $this->_pageFactory->create();
    }
}
