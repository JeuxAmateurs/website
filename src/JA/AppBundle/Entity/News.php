<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * News
 *
 * @ORM\Table(name="ja_news")
 * @ORM\Entity
 */
class News
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @var string
     *
     * @ORM\Column(name="sources", type="string")
     */
    //private $sources;

    /*
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Media")
     */
    //private $medias;

    /*
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Comment", cascade={"persist"})
     */
    //private $comments;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="ownedNews")
     * @ORM\JoinTable(name="ja_news_authors",
     *  joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * )
     */
    private $authors;

    /*
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Team", mappedBy="news")
     */
    //private $authorTeam;

    /**
     * @var Game
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="ownedNews")
     */
    private $game;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Game", mappedBy="referencedNews")
     * @ORM\JoinTable(name="ja_news_mentioned_games",
     *  joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     * )
     */
    private $mentionedGames;


    public function __construct()
    {
        //$this->medias = new ArrayCollection();
        $this->mentionedGames = new ArrayCollection();
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
     * Set "title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get "title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
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
     * Set sources
     *
     * @param string $sources
     * @return News
     */
    public function setSources($sources)
    {
        $this->sources = $sources;

        return $this;
    }

    /**
     * Get sources
     *
     * @return string 
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Set medias
     *
     * @param string $medias
     * @return News
     */
    public function setMedias($medias)
    {
        $this->medias = $medias;

        return $this;
    }

    /**
     * Get medias
     *
     * @return string 
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return News
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set authors
     *
     * @param string $authors
     * @return News
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get authors
     *
     * @return string 
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set authorTeam
     *
     * @param string $authorTeam
     * @return News
     */
    public function setAuthorTeam($authorTeam)
    {
        $this->authorTeam = $authorTeam;

        return $this;
    }

    /**
     * Get authorTeam
     *
     * @return string 
     */
    public function getAuthorTeam()
    {
        return $this->authorTeam;
    }

    /**
     * Set game
     *
     * @param Game $game
     * @return News
     */
    public function setGame($game)
    {
        $this->$game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return array
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return News
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
     * @return News
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add authors
     *
     * @param User $authors
     * @return News
     */
    public function addAuthor(User $authors)
    {
        $authors->removeOwnedNews($this);
        $this->authors[] = $authors;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param User $authors
     */
    public function removeAuthor(User $authors)
    {
        $authors->addOwnedNews($this);
        $this->authors->removeElement($authors);
    }

    /**
     * Add mentionedGames
     *
     * @param Game $mentionedGames
     * @return News
     */
    public function addMentionedGame(Game $mentionedGames)
    {
        $this->mentionedGames[] = $mentionedGames;

        return $this;
    }

    /**
     * Remove mentionedGames
     *
     * @param Game $mentionedGames
     */
    public function removeMentionedGame(Game $mentionedGames)
    {
        $this->mentionedGames->removeElement($mentionedGames);
    }
}
