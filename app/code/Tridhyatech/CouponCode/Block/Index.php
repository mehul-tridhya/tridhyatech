<?php
namespace Tridhyatech\CouponCode\Block;

use Magento\SalesRule\Model\CouponFactory;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var array
     */
    protected $jsLayout;
    protected $_couponFactory;

    /**
     * @var \Tridhyatech\CouponCode\Model\CustomConfigProvider
     */
    protected $configProvider;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tridhyatech\CouponCode\Model\ConfigProvider $configProvider,
        CouponFactory $couponFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_couponFactory = $couponFactory;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->configProvider = $configProvider;
    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        return \Zend_Json::encode($this->jsLayout);
    }

    public function getCustomConfig()
    {
        return $this->configProvider->getCouponCodes();
    }
}