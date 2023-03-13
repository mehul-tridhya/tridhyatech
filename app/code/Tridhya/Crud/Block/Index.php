<?php

namespace Tridhya\Crud\Block;

use Magento\Framework\View\Element\Template;
use Tridhya\Crud\Model\ContactFactory;

class Index extends Template
{
    protected $_contactFactory;

    public function __construct(
        Template\Context $context,
        ContactFactory $contactFactory
    ) {
        parent::__construct($context);
        $this->_contactFactory = $contactFactory;
    }

    public function getContactData()
    {
        $contact = $this->_contactFactory->create();
        $collection = $contact->getCollection();
        return $collection;
    }
}