<?php

namespace Tridhyatech\ApiCrud\Model;

use Tridhyatech\ApiCrud\Api\BlogRepositoryInterface;
use Tridhyatech\ApiCrud\Api\Data\BlogInterface;
use Tridhyatech\ApiCrud\Api\Data\BlogSearchResultInterface;
use Magento\Framework\Api\DataObjectHelper;
use Tridhyatech\ApiCrud\Api\Data\BlogSearchResultInterfaceFactory;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog\CollectionFactory as BlogCollectionFactory;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

class BlogRepository implements BlogRepositoryInterface
{
    /**
     * @var BlogFactory
     */
    private $blogFactory;

    /**
     * @var BlogCollectionFactory
     */
    private $blogCollectionFactory;

    /**
     * @var BlogSearchResultInterfaceFactory
     */
    private $searchResultFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    public function __construct(
        BlogFactory $blogFactory,
        BlogCollectionFactory $blogCollectionFactory,
        BlogSearchResultInterfaceFactory $blogSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
    ) {
        $this->blogFactory = $blogFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->searchResultFactory = $blogSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    public function getById($id)
    {
        $blog = $this->blogFactory->create();
        $blog->getResource()->load($blog, $id);
        if (!$blog->getId()) {
            throw new NoSuchEntityException(__('Unable to find blog with ID "%1"', $id));
        }
        return $blog;
    }

    public function save(BlogInterface $blog)
    {
        $blog->getResource()->save($blog);
        return $blog;
    }

    public function delete(BlogInterface $blog)
    {
        $blog->getResource()->delete($blog);
    }

    public function deleteById($blogId)
    {
        $this->delete($this->getById($blogId));
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $collection = $this->blogCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $preorderItem = [];
        /** @var Test $testModel */
        foreach ($collection as $testModel) {
            $testData = $this->blogFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $testData,
                $testModel->getData(),
                'Tridhyatech\ApiCrud\Api\Data\BlogInterface'
            );
            $preorderItem[] = $this->dataObjectProcessor->buildOutputDataArray(
                $testData,
                'Tridhyatech\ApiCrud\Api\Data\BlogInterface'
            );
        }
        $searchResults->setItems($preorderItem);
        return $searchResults;
    }
}
