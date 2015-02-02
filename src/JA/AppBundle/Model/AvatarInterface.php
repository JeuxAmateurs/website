<?php

namespace JA\AppBundle\Model;

interface AvatarInterface
{
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get avatar
     *
     * @return integer
     */
    public function getAvatar();

    /**
     * Set avatar
     *
     * @param string $avatar
     */
    public function setAvatar($avatar);
}
