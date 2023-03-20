<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as productCollectionFactory;
use Magento\Catalog\Model\CategoryFactory as categoryFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status  as productStatus;
use Magento\Catalog\Model\Product\Visibility  as productVisibility;
use Magento\Catalog\Block\Product\ListProduct  as listProductBlock;
use Magento\Customer\Model\Session  as customerSession;
use Magento\Wishlist\Helper\Data  as wishlistHelper;
use Tridhya\HelloWorld\Helper\Data  as helperData;
use Magento\Framework\View\Layout  as layout;
use Magento\Framework\App\Config\ScopeConfigInterface  as scopeConfig;

class FeaturedProducts implements ArgumentInterface
{
    protected $_productCollectionFactory;
    protected $_categoryFactory;
    protected $_productVisibility;
    protected $_productStatus;
    protected $_customerSession;
    protected $_listProductBlock;
    protected $_wishlistHelper;
    protected $_helperData;
    protected $_layout;
    protected $_scopeConfig;
    protected $_storeManagerInterface;

    const XML_PATH_HEADER = 'slider/feature/heading_text';
    const XML_PATH_ENABLE = 'slider/feature/enable';
    const XML_PATH_ADDTOCART = 'slider/feature/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'slider/feature/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'slider/feature/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'slider/feature/product_per_silde';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    public function __construct(
        productCollectionFactory $productCollectionFactory,
        categoryFactory $categoryFactory,
        productStatus $productStatus,
        productVisibility $productVisibility,
        listProductBlock $listProductBlock,
        customerSession $customerSession,
        wishlistHelper $wishlistHelper,
        helperData $helperData,
        layout $layout,
        scopeConfig $scopeConfig,
        StoreManagerInterface $storeManagerInterface,
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        $this->_listProductBlock = $listProductBlock;
        $this->_customerSession = $customerSession;
        $this->_wishlistHelper = $wishlistHelper;
        $this->_helperData = $helperData;
        $this->_layout = $layout;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManagerInterface = $storeManagerInterface;
    }

    public function getStoreId()
    {
        return $this->_storeManagerInterface->getStore()->getId();
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

    public function getFeaturedProductsCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->setStoreId($this->getStoreId());
        // $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('is_featured', 1);
        $collection->setPageSize($this->getNumberOfProductConfig());
        return $collection;
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

    public function getLayout()
    {
        return $this->_layout;
    }
}
