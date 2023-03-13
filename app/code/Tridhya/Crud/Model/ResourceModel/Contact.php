<?php

namespace Tridhya\Crud\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Contact extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('tridhya_crud_contact', 'id');
    }
}