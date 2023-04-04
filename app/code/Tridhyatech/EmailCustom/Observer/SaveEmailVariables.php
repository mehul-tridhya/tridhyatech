<?php

namespace Tridhyatech\EmailCustom\Observer;

use Magento\Framework\Event\ObserverInterface;

class SaveEmailVariables implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $transport = $observer->getEvent()->getTransport();
        $order = $transport->getOrder();

        if ($order != null) {
            $transport['delivery_note'] = $order->getDeliveryNote();
        }
    }
}
