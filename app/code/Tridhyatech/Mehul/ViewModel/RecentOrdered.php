<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as orderCollectionFactory;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\View\Layout;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class RecentOrdered implements ArgumentInterface
{
    protected $_orderCollectionFactory;
    protected $_storeManagerInterface;
    protected $_scopeConfig;
    protected $_wishlistHelper;
    protected $_listProductBlock;
    protected $_layout;
    protected $_customerSession;
    protected $_productStatus;
    protected $_productCollectionFactory;

    const XML_PATH_HEADER = 'slider/recentordered/heading_text';
    const XML_PATH_ENABLE = 'slider/recentordered/enable';
    const XML_PATH_ADDTOCART = 'slider/recentordered/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'slider/recentordered/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'slider/recentordered/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'slider/recentordered/product_per_silde';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    public function __construct(
        orderCollectionFactory $orderCollectionFactory,
        StoreManagerInterface $storeManagerInterface,
        ScopeConfigInterface $scopeConfig,
        WishlistHelper $wishlistHelper,
        ListProduct $listProductBlock,
        Layout $layout,
        CustomerSession $customerSession,
        ProductStatus $productStatus,
        ProductCollectionFactory $productCollectionFactory,
    ) {
        $this->_storeManagerInterface = $storeManagerInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_wishlistHelper = $wishlistHelper;
        $this->_listProductBlock = $listProductBlock;
        $this->_layout = $layout;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_productStatus = $productStatus;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function getStoreId()
    {
        return $this->_storeManagerInterface->getStore()->getId();
    }

    public function getCustomerData()
    {
        return $this->_customerSession->getCustomerdata() ? $this->_customerSession->getCustomerdata() : null ;
    }

    public function getRecentOrderedProductsCollection()
    {
        $productIds = [];
        $customerId = $this->_customerSession->getCustomerdata()->getId();
        $collection = $this->_orderCollectionFactory->create($customerId)
            ->addAttributeToSelect('*');
        foreach ($collection as $order) {
            $itemsCollection = $order->getItemsCollection();
            foreach ($itemsCollection as $item) {
                $product = $item->getProduct();
                if($product->getVisibility() == \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH && $product->getStatus() == \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
                {
                    $productIds[] = $product->getId();
                }
            }
        }
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $productCollection->setStoreId($this->getStoreId());
        $productCollection->addFieldToFilter('entity_id', array('in' => $productIds));
        return $productCollection;
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
