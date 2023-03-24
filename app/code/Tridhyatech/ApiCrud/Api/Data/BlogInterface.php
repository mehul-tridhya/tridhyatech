<?php

namespace Tridhyatech\ApiCrud\Api\Data;
use Magento\Framework\Api\ExtensibleDataInterface;

interface BlogInterface extends ExtensibleDataInterface
{
    /**
     * @return int
     */
    public function getBlogId();

    /**
     * @param int $blog_id
     * @return $this
     */
    public function setBlogId($blog_id);

    /**
     * @return string
     */
    public function getBlogTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setBlogTitle($title);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getBlogDescription();

    /**
     * @param string $description
     * @return $this
     */
    public function setBlogDescription($description);

    /**
     * @param $updateTime
     * @return $this
     */
    public function setUpdatedAt($updateTime);
}
