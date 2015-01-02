<?php

namespace JA\AppBundle\Model;

/**
 * Game Interface
 *
 */
interface GameInterface
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
     * @return GameInterface $this
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

    /**
     * Set owner
     *
     * @param UserInterface $owner
     * @return GameInterface $this
     */
    public function setOwner(UserInterface $owner);

    /**
     * Get owner
     *
     * @return UserInterface
     */
    public function getOwner();
}
