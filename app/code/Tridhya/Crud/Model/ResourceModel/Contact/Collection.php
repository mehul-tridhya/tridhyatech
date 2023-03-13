<?php

namespace Tridhya\Crud\Model\ResourceModel\Contact;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tridhya\Crud\Model\Contact as ContactModel;
use Tridhya\Crud\Model\ResourceModel\Contact as ContactResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ContactModel::class, ContactResourceModel::class);
    }
}