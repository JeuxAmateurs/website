<?php

namespace JA\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use JA\GameBundle\Model\GameInterface;

/**
 * Game
 *
 * @ORM\MappedSuperclass(repositoryClass="JA\GameBundle\Entity\GameRepository")
 * @ORM\HasLifecycleCallbacks
 */
abstract class Game implements GameInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", unique=true, length=128)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /*
     * @var ArrayCollection
     *
     * ORM\ManyToMany(targetEntity="Technology", inversedBy="games")
     * @ORM\JoinTable(name="games_technologies",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="technology_id", referencedColumnName="id")}
     * )
     */
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Technology")
     * @ORM\JoinTable(name="games_technologies",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="technology_id", referencedColumnName="id")}
     * )
     */
    private $technologies;

    public function  __construct()
    {
        $this->technologies = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Game
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Game
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Game
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add technologies
     *
     * @param Technology $technologies
     * @return Game
     */
    public function addTechnology(Technology $technologies)
    {
        //$technologies->addGame($this);
        $this->technologies[] = $technologies;

        return $this;
    }

    /**
     * Remove technologies
     *
     * @param Technology $technologies
     */
    public function removeTechnology(Technology $technologies)
    {
        //$technologies->removeGame($this);
        $this->technologies->removeElement($technologies);
    }

    /**
     * Get technologies
     *
     * @return Collection
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }
}
