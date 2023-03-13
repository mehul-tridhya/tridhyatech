<?php

namespace Tridhya\EventObserver\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductEvents implements ObserverInterface
{
    /**
     * Below is the method that will fire whenever the event runs!
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $originalName = $product->getName();
        $modifiedName = $originalName . ' - Content added by Magenest ';
        $product->setName($modifiedName);
    }
}
