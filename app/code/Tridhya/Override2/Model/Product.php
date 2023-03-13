<?php

namespace Tridhya\Override2\Model;

class Product extends \Magento\Catalog\Model\Product
{
    public function getName()
    {
        $changeNamebyPreference = $this->_getData('name') . ' modified by Override 2';
        return $changeNamebyPreference;
    }
}
