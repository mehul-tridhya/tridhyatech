<?php

namespace Tridhyatech\ApiCrud\Api\Data;
 
use Magento\Framework\Api\SearchResultsInterface;
 
interface BlogSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Tridhyatech\ApiCrud\Api\Data\BlogInterface[]
     */
    public function getItems();
 
    /**
     * @param \Tridhyatech\ApiCrud\Api\Data\BlogInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
