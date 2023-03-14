<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactory as orderCollectionFactory;
use \Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Wishlist\Helper\Data as WishlistHelper;
use \Magento\Catalog\Block\Product\ListProduct;
use \Magento\Framework\View\Layout;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class RecentOrdered implements ArgumentInterface
{
    protected $_orderCollectionFactory;
    protected $storeManagerInterface;
    protected $scopeConfig;
    protected $wishlistHelper;
    protected $listProductBlock;
    protected $layout;
    protected $_customerSession;
    protected $productStatus;
    protected $productCollectionFactory;

    const XML_PATH_HEADER = 'tridhyatech_general/tridhyatech_recentordered/heading_text';
    const XML_PATH_ENABLE = 'tridhyatech_general/tridhyatech_recentordered/enable';
    const XML_PATH_ADDTOCART = 'tridhyatech_general/tridhyatech_recentordered/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'tridhyatech_general/tridhyatech_recentordered/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'tridhyatech_general/tridhyatech_recentordered/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'tridhyatech_general/tridhyatech_recentordered/product_per_silde';
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
        $this->storeManagerInterface = $storeManagerInterface;
        $this->scopeConfig = $scopeConfig;
        $this->wishlistHelper = $wishlistHelper;
        $this->listProductBlock = $listProductBlock;
        $this->layout = $layout;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->productStatus = $productStatus;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function getStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
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
        $productIds = array_unique($productIds);
        $productCollection = $this->productCollectionFactory->create();
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
