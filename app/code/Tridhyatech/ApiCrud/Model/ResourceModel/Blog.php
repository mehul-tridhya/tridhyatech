<?php

namespace Tridhyatech\ApiCrud\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Blog extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('tridhya_crud_blog', 'blog_id');
    }
}