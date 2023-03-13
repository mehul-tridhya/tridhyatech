<?php

namespace Tridhya\Crud\Model;

use Magento\Framework\Model\AbstractModel;
// use Tridhya\Crud\Model\ResourceModel\Contact as ContactResourceModel;

class Contact extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Tridhya\Crud\Model\ResourceModel\Contact');
    }
}