<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory as MostViewedCollectionFactory;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\View\Layout;

class MostView implements ArgumentInterface
{
    protected $_mostViewedProductsFactory;
    protected $_storeManagerInterface;
    protected $_scopeConfig;
    protected $_wishlistHelper;
    protected $_listProductBlock;
    protected $_layout;

    const XML_PATH_HEADER = 'slider/mostview/heading_text';
    const XML_PATH_ENABLE = 'slider/mostview/enable';
    const XML_PATH_ADDTOCART = 'slider/mostview/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'slider/mostview/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'slider/mostview/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'slider/mostview/product_per_silde';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    public function __construct(
        MostViewedCollectionFactory $mostViewedProductsFactory,
        StoreManagerInterface $storeManagerInterface,
        ScopeConfigInterface $scopeConfig,
        WishlistHelper $wishlistHelper,
        ListProduct $listProductBlock,
        Layout $layout,
    ) {
        $this->_mostViewedProductsFactory = $mostViewedProductsFactory;
        $this->_storeManagerInterface = $storeManagerInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_wishlistHelper = $wishlistHelper;
        $this->_listProductBlock = $listProductBlock;
        $this->_layout = $layout;
    }

    public function getStoreId()
    {
        return $this->_storeManagerInterface->getStore()->getId();
    }

    public function getMostViewProductCollection()
    {
        $collection = $this->_mostViewedProductsFactory->create()
            ->addAttributeToSelect('*')
            ->addViewsCount()
            ->setStoreId($this->getStoreId())
            ->addStoreFilter($this->getStoreId());
        return $collection;
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

    public function getProductPrice($product)
    {
        $abstractProductBlock = $this->_layout->createBlock('\Magento\Catalog\Block\Product\AbstractProduct');
        return $abstractProductBlock->getProductPriceHtml($product,\Magento\Catalog\Ui\DataProvider\Product\Listing\Collector\Price::KEY_FINAL_PRICE);
    }

    public function getAddToCartPostParams($product)
    {
        return $this->_listProductBlock->getAddToCartPostParams($product);
    }

    public function getAddToWishlistParams($product)
    {
        return $this->_wishlistHelper->getAddParams($product);
    }

    public function getHeaderText()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_HEADER, self::SCOPE_STORE);
    }

    public function getIsBlockEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_ENABLE, self::SCOPE_STORE);
    }

    public function getIsAddtoCartEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_ADDTOCART, self::SCOPE_STORE);
    }

    public function getIsAddtoWishlistEnable()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_ADDTOWISHLIST, self::SCOPE_STORE);
    }

    public function getNumberOfProductConfig()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_NUMBER_OF_PRODUCT, self::SCOPE_STORE);
    }

    public function getProductPerSildeConfig()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_PRODUCT_PER_SLIDE, self::SCOPE_STORE);
    }
}
