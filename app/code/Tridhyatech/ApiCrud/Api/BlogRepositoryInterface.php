<?php

namespace Tridhyatech\ApiCrud\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Tridhyatech\ApiCrud\Api\Data\BlogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface BlogRepositoryInterface
 *
 * @api
 */
interface BlogRepositoryInterface
{
    /**
     * Create or update a Blog.
     *
     * @param \Tridhyatech\ApiCrud\Api\Data\BlogInterface $blog
     * @return \Tridhyatech\ApiCrud\Api\Data\BlogInterface
     */
    public function save(BlogInterface $blog);

    /**
     * Get a Blog by Id
     *
     * @param int $id
     * @return \Tridhyatech\ApiCrud\Api\Data\BlogInterface
     * @throws NoSuchEntityException If Blog with the specified ID does not exist.
     * @throws LocalizedException
     */
    public function getById($id);

    /**
     * Delete a Blog
     *
     * @param \Tridhyatech\ApiCrud\Api\Data\BlogInterface $blog
     * @return \Tridhyatech\ApiCrud\Api\Data\BlogInterface
     * @throws NoSuchEntityException If Blog with the specified ID does not exist.
     * @throws LocalizedException
     */
    public function delete(BlogInterface $blog);

    /**
     * Undocumented function
     *
     * @param int $blogId
     * @return void
     */
    public function deleteById($blogId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Tridhyatech\ApiCrud\Api\Data\BlogSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
