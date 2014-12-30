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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Game", mappedBy="owner")
     */
    protected $ownedGames;

    public function __construct()
    {
        parent::__construct();

        $this->ownedGames = new ArrayCollection();
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
}
