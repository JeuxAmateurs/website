<?php

namespace JA\AppBundle\Handler;

use JA\AppBundle\Model\NewsInterface;

interface NewsHandlerInterface
{
    /**
     * Get all News
     *
     * @api
     *
     * @return array
     */
    public function getAll();

    /**
     * Get a News given the identifier
     *
     * @api
     *
     * @param unsigned int $id
     *
     * @return NewsInterface
     */
    public function get($id);

    /**
     * Create a News.
     *
     * @param array|null $parameters
     *
     * @return NewsInterface
     */
    public function post($parameters);

    /**
     * Edit a given News
     * @todo : or create it if it don't exist
     *
     * @param NewsInterface $news
     * @param array|null $parameters
     *
     * @return NewsInterface
     */
    public function put(NewsInterface $news, $parameters);

    /**
     * Partially update a given News
     *
     * @param NewsInterface $news
     * @param array|null $parameters
     *
     * @return NewsInterface
     */
    public function patch(NewsInterface $nwes, $parameters);

    /**
     * Delete a given News
     *
     * @param NewsInterface $news
     *
     */
    public function delete(NewsInterface $news);
}
