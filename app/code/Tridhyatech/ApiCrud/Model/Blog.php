<?php
namespace Tridhyatech\ApiCrud\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Tridhyatech\ApiCrud\Api\data\BlogInterface;
use Tridhyatech\ApiCrud\Model\ResourceModel\Blog as ResourceModel;

/**
 * Class Blog
 */
class Blog extends AbstractModel implements
    BlogInterface,
    IdentityInterface
{
    const CACHE_TAG = 'tridhya_crud_blog';

    /**
     * Init
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getBlogId()
    {
        return $this->getData('blog_id');
    }
    public function setBlogId($blog_id)
    {
        return $this->setData('blog_id', $blog_id);
    }
    public function getBlogTitle()
    {
        return $this->getData('blog_title');
    }
    public function setBlogTitle($title)
    {
        return $this->setData('blog_title', $title);
    }
    public function getStatus()
    {
        return $this->getData('status');
    }
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }
    public function getBlogDescription()
    {
        return $this->getData('blog_description');
    }
    public function setBlogDescription($description)
    {
        return $this->setData('blog_description', $description);
    }
    public function setUpdatedAt($updateTime)
    {
        return $this->setData('updated_at', $updateTime);
    }
}