<?php

namespace Tridhyatech\EmailCustom\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
 
use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\ShippingInformationManagement as newShippingInformationManagement;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
class ShippingInformationManagement
{
    public $cartRepository;
    public function __construct(
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function beforeSaveAddressInformation(
        newShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $quote = $this->cartRepository->getActive($cartId);
        $shippingAddress = $addressInformation->getShippingAddress();
        $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();
        if ($shippingAddressExtensionAttributes) {
            $deliveryNote = $shippingAddressExtensionAttributes->getDeliveryNote();
            $deliveryDate = $shippingAddressExtensionAttributes->getDeliveryDate();
            $shippingAddress->setDeliveryNote($deliveryNote);
            $quote->setDeliveryNote($deliveryNote);
            $shippingAddress->setDeliveryDate($deliveryDate);
            $quote->setDeliveryDate($deliveryDate);
            $this->cartRepository->save($quote);
        }
    }
}
