<?php

namespace Tridhya\EventObserver\Observer;

class DisplayText implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $displayText = $observer->getData('mp_text');
        echo $displayText->getText() . " - Some content </br>";
        $displayText->setText('Execute event successfully.');
        return $this;
    }
}
