<?php

namespace Tridhyatech\ApiCrud\Model;

use Tridhyatech\ApiCrud\Api\BlogRepositoryInterface;
use Tridhyatech\ApiCrud\Api\Data\BlogInterface;
use Tridhyatech\ApiCrud\Api\Data\BlogSearchResultInterface;
use Tridhyatech\ApiCrud\Api\Data\BlogSearchResultInterfaceFactory;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog\CollectionFactory as BlogCollectionFactory;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;

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

    public function __construct(
        BlogFactory $blogFactory,
        BlogCollectionFactory $blogCollectionFactory,
        BlogSearchResultInterfaceFactory $amastySearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->blogFactory = $blogFactory;
        $this->blogCollectionFactory = $blogCollectionFactory;
        $this->searchResultFactory = $amastySearchResultInterfaceFactory;
       $this->collectionProcessor = $collectionProcessor;
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
       $collection = $this->blogCollectionFactory->create();
       $this->collectionProcessor->process($searchCriteria, ($collection));
       $searchResults = $this->searchResultFactory->create();
 
       $searchResults->setSearchCriteria($searchCriteria);
       $searchResults->setItems($collection->getItems());
 
       return $searchResults;
    }
}
