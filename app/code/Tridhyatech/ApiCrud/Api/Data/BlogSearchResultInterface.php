<?php

namespace Tridhyatech\ApiCrud\Api\Data;
 
use Magento\Framework\Api\SearchResultsInterface;
 
interface BlogSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Amasty\Example\Api\Data\AmastyInterface[]
     */
    public function getItems();
 
    /**
     * @param \Amasty\Example\Api\Data\AmastyInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
