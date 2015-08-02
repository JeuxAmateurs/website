<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

use JA\AppBundle\Model\GameInterface;
use JA\AppBundle\Model\UserInterface;
use JA\AppBundle\Model\AvatarInterface;

/**
 * User
 *
 * @ORM\Table(name="ja_user")
 * @ORM\Entity(repositoryClass="JA\AppBundle\Entity\UserRepository")
 */
class User extends BaseUser implements UserInterface, AvatarInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=100, nullable=true)
     */
    protected $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text", nullable=true)
     */
    protected $biography;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Skill", inversedBy="users")
     * @ORM\JoinTable(name="ja_user_skills",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="name_canonical")},
     * )
     */
    protected $skills;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Game", mappedBy="owner")
     */
    protected $ownedGames;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="News", mappedBy="authors")
     */
    protected $ownedNews;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Game", inversedBy="favoritesUsers")
     * @ORM\JoinTable(name="ja_user_favorite_games",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id", onDelete="CASCADE")},
     * )
     */
    protected $favoritesGames;

    public function __construct()
    {
        parent::__construct();

        $this->skills = new ArrayCollection();
        $this->ownedGames = new ArrayCollection();
        $this->ownedNews = new ArrayCollection();
        $this->favoritesGames = new ArrayCollection();
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
     * Get avatar
     *
     * @return integer
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get biography
     *
     * @return integer
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set biography
     *
     * @param string $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * Add a skill
     *
     * @param Skill $skill
     * @return $this
     */
    public function addSkill(Skill $skill)
    {
        $this->skills[] = $skill;
        $skill->addUser($this);

        return $this;
    }

    /**
     * Remove a skill
     *
     * @param Skill $skill
     */
    public function removeSkill(Skill $skill)
    {
        $this->skills->removeElement($skill);
    }

    /**
     * Get skills
     *
     * @return ArrayCollection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add ownedGame
     *
     * @param GameInterface $ownedGame
     * @return User
     */
    public function addOwnedGame(GameInterface $ownedGame)
    {
        $this->ownedGames[] = $ownedGame;
        $ownedGame->setOwner($this);

        return $this;
    }

    /**
     * Remove one element to ownedGames
     *
     * @param GameInterface $ownedGame
     */
    public function removeOwnedGame(GameInterface $ownedGame)
    {
        $this->ownedGames->removeElement($ownedGame);
    }

    /**
     * Get ownedGames
     *
     * @return ArrayCollection
     */
    public function getOwnedGames()
    {
        return $this->ownedGames;
    }

    /**
     * Add ownedNews
     *
     * @param News $ownedNews
     * @return User
     */
    public function addOwnedNews(News $ownedNews)
    {
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
        $this->ownedNews->removeElement($ownedNews);
    }

    /**
     * Get ownedNews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwnedNews()
    {
        return $this->ownedNews;
    }

    /**
     *
     * Get all favorites games
     *
     * @return ArrayCollection
     */
    public function getFavoritesGames()
    {
        return $this->favoritesGames;
    }

    /**
     * Add new favorite game
     *
     * @param Game $newGame
     */
    public function addFavoriteGame(Game $favGame)
    {
        $this->favoritesGames->add($favGame);
        $favGame->addFavoriteUser($this);
    }

    /**
     * Remove a game from favorites
     *
     * @param Game $favGame
     */
    public function removeFavoriteGame(Game $favGame)
    {
        $this->favoritesGames->removeElement($favGame);
        $favGame->removeFavoriteUser($this);
    }

    /**
     * Remove all games from favorites
     */
    public function removeFavoritesGames()
    {
        $this->favoritesGames->clear();
    }
}
