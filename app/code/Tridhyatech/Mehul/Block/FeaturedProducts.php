<?php

namespace Tridhyatech\Mehul\Block;

use Magento\Framework\View\Element\Template;

class FeaturedProducts extends Template
{
    protected $productCollectionFactory;
    protected $categoryFactory;
    protected $productVisibility;
    protected $productStatus;
    protected $_customerSession;
    protected $listProductBlock;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->listProductBlock = $listProductBlock;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
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
        // $collection->addAttributeToSelect(array('name','image','price'));
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('is_featured', 1);
        $collection->setPageSize(10);
        return $collection;
    }

    public function getProductPrice($product)
    {
        $abstractProductBlock = $this->getLayout()->createBlock('\Magento\Catalog\Block\Product\AbstractProduct');
        return $abstractProductBlock->getProductPrice($product);
    }

    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    public function getCustomer()
    {
        echo $this->_customerSession->getCustomer()->getId(); //Get Current customer ID
        $customerData = $this->_customerSession->getCustomer(); //Get Current Customer Data
        print_r($customerData->getData());
    }
}
