<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use JA\AppBundle\Model\GameInterface;
use JA\AppBundle\Model\UserInterface;

/**
 * Game
 *
 * @ORM\Table(name="ja_game")
 * @ORM\Entity(repositoryClass="JA\AppBundle\Entity\GameRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Game implements GameInterface
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

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Technology", inversedBy="games")
     * @ORM\JoinTable(name="ja_games_technologies",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="technology_id", referencedColumnName="id")},
     * )
     */
    private $technologies;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedGames")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")//, nullable=false)
     */
    protected $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    protected $ownedNews;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="News", inversedBy="mentionedGames")
     * @ORM\JoinTable(name="ja_games_ref_news",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="technology_id", referencedColumnName="id")},
     * )
     */
    protected $referencedNews;

    public function  __construct()
    {
        $this->technologies = new ArrayCollection();
        $this->ownedNews = new ArrayCollection();
        $this->referencedNews = new ArrayCollection();
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
     * @return GameInterface $this
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
     * @return GameInterface $this
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
     * @return Game $this
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
     * @return Game $this
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
     * @return GameInterface $this
     */
    public function addTechnology(Technology $technologies)
    {
        $technologies->addGame($this);
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
        $technologies->removeGame($this);
        $this->technologies->removeElement($technologies);
    }

    /**
     * Get technologies
     *
     * @return ArrayCollection
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * Set owner
     *
     * @param UserInterface $owner
     * @return GameInterface $this
     */
    public function setOwner(UserInterface $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return UserInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add ownedNews
     *
     * @param News $ownedNews
     * @return Game
     */
    public function addOwnedNews(News $ownedNews)
    {
        $ownedNews->setGame($this);
        $this->ownedNews[] = $ownedNews;

        return $this;
    }

    /**
     * Remove ownedNews
     *
     * @param News $ownedNews
     */
    public function removeOwnedNews(News $ownedNews)
    {
        $ownedNews->setGame(null);
        $this->ownedNews->removeElement($ownedNews);
    }

    /**
     * Get ownedNews
     *
     * @return Collection
     */
    public function getOwnedNews()
    {
        return $this->ownedNews;
    }

    /**
     * Add referencedNews
     *
     * @param News $referencedNews
     * @return Game
     */
    public function addReferencedNews(News $referencedNews)
    {
        $referencedNews->addMentionedGame($this);
        $this->referencedNews[] = $referencedNews;

        return $this;
    }

    /**
     * Remove referencedNews
     *
     * @param News $referencedNews
     */
    public function removeReferencedNews(News $referencedNews)
    {
        $referencedNews->removeMentionedGame($this);
        $this->referencedNews->removeElement($referencedNews);
    }

    /**
     * Get referencedNews
     *
     * @return Collection
     */
    public function getReferencedNews()
    {
        return $this->referencedNews;
    }
}
