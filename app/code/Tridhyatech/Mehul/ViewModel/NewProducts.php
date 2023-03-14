<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class NewProducts implements ArgumentInterface
{
    protected $productCollectionFactory;
    protected $categoryFactory;
    protected $productVisibility;
    protected $productStatus;
    protected $_customerSession;
    protected $listProductBlock;
    protected $wishlistHelper;
    protected $helperData;
    protected $layout;
    protected $scopeConfig;

    const XML_PATH_HEADER = 'tridhyatech_general/tridhyatech_new/heading_text';
    const XML_PATH_ENABLE = 'tridhyatech_general/tridhyatech_new/enable';
    const XML_PATH_ADDTOCART = 'tridhyatech_general/tridhyatech_new/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'tridhyatech_general/tridhyatech_new/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'tridhyatech_general/tridhyatech_new/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'tridhyatech_general/tridhyatech_new/product_per_silde';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Helper\Data $wishlistHelper,
        \Tridhya\HelloWorld\Helper\Data $helperData,
        \Magento\Framework\View\Layout $layout,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->listProductBlock = $listProductBlock;
        $this->_customerSession = $customerSession;
        $this->wishlistHelper = $wishlistHelper;
        $this->helperData = $helperData;
        $this->layout = $layout;
        $this->scopeConfig = $scopeConfig;
    }

    public function getImageUrl($product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');
        $imageUrl = $helperImport->init($product, 'product_page_image_small')
            ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
            ->resize(380)
            ->getUrl();
        return $imageUrl;
    }

    public function getNewProductsCollection()
    {
        $todayDate = date('Y-m-d');
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->addAttributeToSelect('*');
        $collection ->addAttributeToFilter(
                'news_from_date',
                ['date' => true, 'to' => $todayDate],
                'left'
            )
            ->addAttributeToFilter(
                'news_from_date',
                ['is' => new \Zend_Db_Expr('not null')],
                'left'
            )
            ->addAttributeToFilter(
                'news_to_date',
                ['date' => true, 'from' => $todayDate],
                'left'
            )
            ->addAttributeToFilter(
                'news_to_date',
                ['is' => new \Zend_Db_Expr('not null')],
                'left'
            )
            ->addAttributeToSort(
                'news_from_date',
                'desc'
            );
        $collection->setPageSize($this->getNumberOfProductConfig());
        return $collection;
    }

    public function getEndOfDayDate()
    {
        return ;
    }

    public function getStartOfDayDate()
    {
        return ;
    }

    public function getProductPrice($product)
    {
        $abstractProductBlock = $this->layout->createBlock('\Magento\Catalog\Block\Product\AbstractProduct');
        return $abstractProductBlock->getProductPrice($product);
    }

    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    public function getAddToWishlistParams($product)
    {
        return $this->wishlistHelper->getAddParams($product);
    }

    public function getHeaderText()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HEADER, self::SCOPE_STORE);
    }

    public function getIsBlockEnable()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLE, self::SCOPE_STORE);
    }

    public function getIsAddtoCartEnable()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ADDTOCART, self::SCOPE_STORE);
    }

    public function getIsAddtoWishlistEnable()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ADDTOWISHLIST, self::SCOPE_STORE);
    }

    public function getNumberOfProductConfig()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_NUMBER_OF_PRODUCT, self::SCOPE_STORE);
    }

    public function getProductPerSildeConfig()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PRODUCT_PER_SLIDE, self::SCOPE_STORE);
    }
}
