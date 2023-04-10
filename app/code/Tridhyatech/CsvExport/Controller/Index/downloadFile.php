<?php

namespace Tridhyatech\CsvExport\Controller\Index;

use Laminas\Db\Sql\Where;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Reports\Model\ResourceModel\Product\Lowstock\CollectionFactory;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Framework\Data\Collection as DataCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as productFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\API\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;

use Psr\Log\LoggerInterface;

class downloadFile extends Action
{
    protected $_resultPageFactory;
    protected $_downloader;
    protected $_logger;
    protected $_directory;
    protected $_fileDirectory;
    protected $_storeManager;
    protected $_lowstocksFactory;
    protected $_productCollectionFactory;
    protected $_productVisibility;
    protected $_productCollectionInterface;
    protected $_stockConfiguration;

    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        LoggerInterface $logger,
        DirectoryList $directory,
        PageFactory $resultPageFactory,
        StoreManagerInterface $storeManager,
        Filesystem $fileDirectory,
        CollectionFactory $lowstocksFactory,
        productFactory $productCollectionFactory,
        Visibility $productVisibility,
        ProductRepositoryInterface $productCollectionInterface,
        StockConfigurationInterface $stockConfiguration
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
        $this->_logger = $logger;
        $this->_downloader = $fileFactory;
        $this->_lowstocksFactory = $lowstocksFactory;
        $this->_storeManager = $storeManager;
        $this->_directory = $directory;
        $this->_fileDirectory = $fileDirectory->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_productCollectionInterface = $productCollectionInterface;
        $this->_stockConfiguration = $stockConfiguration;
        parent::__construct($context);
    }

    public function execute()
    {
        $website = $this->getRequest()->getParam('website');
        $group = $this->getRequest()->getParam('group');
        $store = $this->getRequest()->getParam('store');

        if ($website) {
            $storeIds = $this->_storeManager->getWebsite($website)->getStoreIds();
            $storeId = array_pop($storeIds);
        } elseif ($group) {
            $storeIds = $this->_storeManager->getGroup($group)->getStoreIds();
            $storeId = array_pop($storeIds);
        } elseif ($store) {
            $storeId = (int)$store;
        } else {
            $storeId = null;
        }
        $filePath = $this->createCsv($storeId);
        $this->downloadFile($filePath);
    }

    public function getProductsCollection($storeId)
    {

        /** @var $collection Collection  */
        $collection = $this->_lowstocksFactory->create()->addAttributeToSelect(
            '*'
        )->filterByIsQtyProductTypes()
            ->joinInventoryItem(
                'qty'
            )->joinInventoryItem(
                'notify_stock_qty'
            )->useManageStockFilter(
                $storeId
            )->useNotifyStockQtyFilter(
                $storeId
            )->setOrder(
                'qty',
                DataCollection::SORT_ORDER_ASC
            )->addAttributeToFilter(
                'status',
                Status::STATUS_ENABLED
            );

        if ($storeId) {
            $collection->addStoreFilter($storeId);
        }

        return $collection;
    }

    public function createCsv($storeId)
    {
        $filepath = 'export/' . "ListProducts" . date('Ymd_His') . ".csv";
        $this->_fileDirectory->create('export');
        $stream = $this->_fileDirectory->openFile($filepath, 'w+');
        $stream->lock();
        $header = ['Sku', 'Qty', 'Suggested Qty'];
        $stream->writeCsv($header);
        $productCollectionData = $this->getProductsCollection($storeId)->getData();

        foreach ($productCollectionData as $product) {
            $data = [];
            $data[] = $product['sku'];
            $data[] = $product['qty'];
            $data[] = $product['notify_stock_qty'] ? $product['notify_stock_qty'] + 10 : (int)$this->_stockConfiguration->getNotifyStockQty($storeId) + 10;
            $stream->writeCsv($data);
        }
        return $filepath;
    }

    public function downloadFile($filePath)
    {
        $fileName = "LowStockProducts" . date('Ymd_His') . ".csv";
        try {
            return $this->_downloader->create(
                $fileName,
                [
                    'type' => 'filename',
                    'value' => $filePath,
                ],
                $this->_directory::VAR_DIR
            );
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }
}
