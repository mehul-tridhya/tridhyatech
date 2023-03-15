<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use \Magento\Reports\Model\ResourceModel\Product\CollectionFactory as MostViewedCollectionFactory;
use \Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Wishlist\Helper\Data as WishlistHelper;
use \Magento\Catalog\Block\Product\ListProduct;
use \Magento\Framework\View\Layout;

class MostView implements ArgumentInterface
{
    protected $_mostViewedProductsFactory;
    protected $storeManagerInterface;
    protected $scopeConfig;
    protected $wishlistHelper;
    protected $listProductBlock;
    protected $layout;

    const XML_PATH_HEADER = 'tridhyatech_general/tridhyatech_mostview/heading_text';
    const XML_PATH_ENABLE = 'tridhyatech_general/tridhyatech_mostview/enable';
    const XML_PATH_ADDTOCART = 'tridhyatech_general/tridhyatech_mostview/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'tridhyatech_general/tridhyatech_mostview/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'tridhyatech_general/tridhyatech_mostview/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'tridhyatech_general/tridhyatech_mostview/product_per_silde';
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
        $this->storeManagerInterface = $storeManagerInterface;
        $this->scopeConfig = $scopeConfig;
        $this->wishlistHelper = $wishlistHelper;
        $this->listProductBlock = $listProductBlock;
        $this->layout = $layout;
    }

    public function getStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
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
        $abstractProductBlock = $this->layout->createBlock('\Magento\Catalog\Block\Product\AbstractProduct');
        return $abstractProductBlock->getProductPriceHtml($product,\Magento\Catalog\Ui\DataProvider\Product\Listing\Collector\Price::KEY_FINAL_PRICE);
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
