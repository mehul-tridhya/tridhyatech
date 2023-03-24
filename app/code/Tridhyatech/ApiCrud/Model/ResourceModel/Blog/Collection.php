<?php

namespace Tridhyatech\ApiCrud\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tridhyatech\ApiCrud\Model\Blog as BlogModel;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog as BlogResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(BlogModel::class, BlogResourceModel::class);
    }
}