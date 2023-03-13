<?php

namespace Tridhya\Override\Plugin;

use Magento\Catalog\Model\Product;

class ProductPlugin
{
    public function afterGetName(Product $subject, $result)
    {
        return $result . ' modified by After Plugin';
    }
}
