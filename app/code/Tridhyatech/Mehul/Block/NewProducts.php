<?php

namespace Tridhyatech\Mehul\Block;

use Magento\Framework\View\Element\Template;

class NewProducts extends Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}