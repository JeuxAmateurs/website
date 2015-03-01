<?php

namespace JA\AppBundle\Handler;

use JA\AppBundle\Model\TechnologyInterface;

interface TechnologyHandlerInterface
{
    /**
     * Get all technologies
     *
     * @api
     *
     * @return array
     */
    public function getAll();

    /**
     * Get a Technology given the identifier
     *
     * @api
     *
     * @param string $slug
     *
     * @return TechnologyInterface
     */
    public function get($slug);

    /**
     * Create a new Technology
     *
     * @param array|null $parameters
     *
     * @return TechnologyInterface
     */
    public function post($parameters);

    /**
     * Edit a given Technology
     * @todo : or create it if it don't exist
     *
     * @param TechnologyInterface $technology
     * @param array|null $parameters
     *
     * @return TechnologyInterface
     */
    public function put(TechnologyInterface $technology, $parameters);

    /**
     * Partially update a given Technology
     *
     * @param TechnologyInterface $technology
     * @param array|null $parameters
     *
     * @return TechnologyInterface
     */
    public function patch(TechnologyInterface $technology, $parameters);

    /**
     * Delete a given Technology
     *
     * @param TechnologyInterface $technology
     *
     */
    public function delete(TechnologyInterface $technology);
}