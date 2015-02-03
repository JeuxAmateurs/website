<?php

namespace JA\AppBundle\Model;

/**
 * Technology Interface
 *
 */
interface TechnologyInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     * @return TechnologyInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set description
     *
     * @param string $description
     * @return TechnologyInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set content
     *
     * @param string $content
     * @return TechnologyInterface
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();
}
