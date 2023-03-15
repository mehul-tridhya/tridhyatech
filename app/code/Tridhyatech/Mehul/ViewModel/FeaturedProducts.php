<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use \Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;

class FeaturedProducts implements ArgumentInterface
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
    protected $storeManagerInterface;

    const XML_PATH_HEADER = 'tridhyatech_general/tridhyatech_feature/heading_text';
    const XML_PATH_ENABLE = 'tridhyatech_general/tridhyatech_feature/enable';
    const XML_PATH_ADDTOCART = 'tridhyatech_general/tridhyatech_feature/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'tridhyatech_general/tridhyatech_feature/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'tridhyatech_general/tridhyatech_feature/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'tridhyatech_general/tridhyatech_feature/product_per_silde';
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
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManagerInterface,
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
        $this->storeManagerInterface = $storeManagerInterface;
    }

    public function getStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
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
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
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

    public function getLayout()
    {
        return $this->layout;
    }
}
