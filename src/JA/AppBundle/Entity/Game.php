<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use JA\AppBundle\Model\GameInterface;
use JA\AppBundle\Model\NewsInterface;
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
     * @ORM\ManyToMany(targetEntity="Technology", inversedBy="games", cascade={"persist"})
     * @ORM\JoinTable(name="ja_games_technologies",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="technology_id", referencedColumnName="id", onDelete="CASCADE")},
     * )
     */
    private $technologies;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedGames")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")//, nullable=false)
     */
    protected $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="News", mappedBy="game")
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

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="favoritesGames")
     * @ORM\JoinTable(name="ja_user_favorite_games",
     *  joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     * )
     */
    protected $favoritesUsers;

    public function  __construct()
    {
        $this->technologies = new ArrayCollection();
        $this->ownedNews = new ArrayCollection();
        $this->referencedNews = new ArrayCollection();
        $this->favoritesUsers = new ArrayCollection();
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
     * @param NewsInterface $ownedNews
     * @return Game $this
     */
    public function addOwnedNews(NewsInterface $ownedNews)
    {
        $ownedNews->setGame($this);
        $this->ownedNews[] = $ownedNews;

        return $this;
    }

    /**
     * Remove ownedNews
     *
     * @param NewsInterface $ownedNews
     */
    public function removeOwnedNews(NewsInterface $ownedNews)
    {
        $ownedNews->setGame(null);
        $this->ownedNews->removeElement($ownedNews);
    }

    /**
     * Get ownedNews
     *
     * @return ArrayCollection
     */
    public function getOwnedNews()
    {
        return $this->ownedNews;
    }

    /**
     * Add referencedNews
     *
     * @param NewsInterface $referencedNews
     * @return Game
     */
    public function addReferencedNews(NewsInterface $referencedNews)
    {
        $referencedNews->addMentionedGame($this);
        $this->referencedNews[] = $referencedNews;

        return $this;
    }

    /**
     * Remove referencedNews
     *
     * @param NewsInterface $referencedNews
     */
    public function removeReferencedNews(NewsInterface $referencedNews)
    {
        $referencedNews->removeMentionedGame($this);
        $this->referencedNews->removeElement($referencedNews);
    }

    /**
     * Get referencedNews
     *
     * @return ArrayCollection
     */
    public function getReferencedNews()
    {
        return $this->referencedNews;
    }

    /**
     * Return all users who marked the game as favorite
     *
     * @return ArrayCollection
     */
    public function getFavoritesUsers()
    {
        return $this->favoritesUsers;
    }

    /**
     * Add a new user who marked the game as favorite
     *
     * @param User $user
     */
    public function addFavoriteUser(User $user)
    {
        $this->favoritesUsers->add($user);
    }

    /**
     * The user doesn't favorize this game anymore
     *
     * @param User $user
     */
    public function removeFavoriteUser(User $user)
    {
        $this->favoritesUsers->removeElement($user);
    }

    /**
     * Remove all users who marked the game as favorite
     */
    public function removeFavoritesUsers()
    {
        $this->favoritesUsers->clear();
    }
}
