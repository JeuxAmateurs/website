<?php

namespace JA\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ja_skills")
 */
class Skill
{
    /**
     * @var string $nameCanonical
     *
     * @ORM\Id()
     * @ORM\Column(name="name_canonical", type="string")
     */
    private $nameCanonical;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var ArrayCollection $user
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="skills")
     * @ORM\JoinTable(name="ja_user_skills",
     *  joinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="name_canonical")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * )
     */
    private $users;

    public function __construct($nameCanonical, $name)
    {
        $this->setNameCanonical($nameCanonical);
        $this->setName($name);

        $this->users = new ArrayCollection();
    }

    /**
     * Get canonical name
     *
     * @return string
     */
    public function getNameCanonical()
    {
        return $this->nameCanonical;
    }

    /**
     * Set canonical name
     *
     * @param $nameCanonical
     * @return $this
     */
    public function setNameCanonical($nameCanonical)
    {
        $this->nameCanonical = $nameCanonical;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the users sharing the same skill
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add a user to this skill
     *
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove the user from this skill
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }
}