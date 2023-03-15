<?php

namespace Tridhyatech\Mehul\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\View\Layout;
use \Magento\Wishlist\Helper\Data as WishlistHelper;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use \Magento\Catalog\Model\ProductRepository as ProductRepository;
use \Magento\ConfigurableProduct\Model\Product\Type\Configurable as Configurable;

class Bestsellers implements ArgumentInterface
{
    protected $_bestSellersCollectionFactory;
    protected $productCollectionFactory;
    protected $listProductBlock;
    protected $layout;
    protected $wishlistHelper;
    protected $scopeConfig;
    protected $productStatus;
    protected $_productRepository;
    protected $configurable;

    const XML_PATH_HEADER = 'tridhyatech_general/tridhyatech_bestsellers/heading_text';
    const XML_PATH_ENABLE = 'tridhyatech_general/tridhyatech_bestsellers/enable';
    const XML_PATH_ADDTOCART = 'tridhyatech_general/tridhyatech_bestsellers/add_to_cart';
    const XML_PATH_ADDTOWISHLIST = 'tridhyatech_general/tridhyatech_bestsellers/add_to_wishlist';
    const XML_PATH_NUMBER_OF_PRODUCT = 'tridhyatech_general/tridhyatech_bestsellers/number_of_product';
    const XML_PATH_PRODUCT_PER_SLIDE = 'tridhyatech_general/tridhyatech_bestsellers/product_per_silde';
    const SCOPE_STORE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        BestSellersCollectionFactory $bestSellersCollectionFactory,
        ListProduct $listProductBlock,
        Layout $layout,
        WishlistHelper $wishlistHelper,
        ScopeConfigInterface $scopeConfig,
        ProductStatus $productStatus,
        ProductRepository $productRepository,
        Configurable $configurable,
    ) {
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->listProductBlock = $listProductBlock;
        $this->layout = $layout;
        $this->wishlistHelper = $wishlistHelper;
        $this->scopeConfig = $scopeConfig;
        $this->productStatus = $productStatus;
        $this->_productRepository = $productRepository;
        $this->configurable = $configurable;
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

    public function getParentProductId($childProductId)
    {
        $parentConfigObject = $this->configurable->getParentIdsByChild($childProductId);
        if ($parentConfigObject) {
            return $parentConfigObject[0];
        }
        return false;
    }

    public function getBestsellersProductsCollection()
    {
        $productIds = [];
        $bestSellers = $this->_bestSellersCollectionFactory->create()
            ->setPeriod('day');
        foreach ($bestSellers as $product) {
            $parentProductId = $this->getParentProductId($product->getProductId());
            if($parentProductId)
            {
                $productIds[] = $parentProductId;
            }else{
                $productIds[] = $product->getProductId();
            }
        }
        $collection = $this->productCollectionFactory->create()->addIdFilter($productIds);
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->setPageSize($this->getNumberOfProductConfig());
        return $collection;
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
