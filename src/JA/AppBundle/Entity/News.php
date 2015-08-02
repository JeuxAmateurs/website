<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use JA\AppBundle\Model\NewsInterface;
use JA\AppBundle\Model\GameInterface;
use JA\AppBundle\Model\UserInterface;

/**
 * News
 *
 * @ORM\Table(name="ja_news")
 * @ORM\Entity
 */
class News implements NewsInterface
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
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", unique=true, length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

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
    protected $authors;

    /**
     * @var Game
     *
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="ownedNews")
     * @ORM\JoinColumn(onDelete="SET NULL") // when the game is deleted, the news still stays
     */
    protected $game;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Game", mappedBy="referencedNews")
     * @ORM\JoinTable(name="ja_news_mentioned_games",
     *  joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="CASCADE")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id", onDelete="CASCADE")},
     * ) // when the news or game is deleted, the join table is deleted
     */
    protected $mentionedGames;


    public function __construct()
    {
        //$this->medias = new ArrayCollection();
        $this->mentionedGames = new ArrayCollection();
        $this->authors = new ArrayCollection();
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
     * Set title
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
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set content
     *
     * @param string $content
     * @return NewsInterface
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
     * @return NewsInterface
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
     * @return NewsInterface
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
     * @return NewsInterface
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
     * Set game
     *
     * @param GameInterface $game
     * @return News
     */
    public function setGame(GameInterface $game)
    {
        $this->game = $game;

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
     * Get authors
     *
     * @return ArrayCollection
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Add authors
     *
     * @param UserInterface $author
     * @return NewsInterface
     */
    public function addAuthor(UserInterface $author)
    {
        $author->removeOwnedNews($this);
        $this->authors[] = $author;

        return $this;
    }

    /**
     * Remove authors
     *
     * @param UserInterface $author
     */
    public function removeAuthor(UserInterface $author)
    {
        $author->addOwnedNews($this);
        $this->authors->removeElement($author);
    }

    /**
     * Get mentionedGames
     *
     * @return ArrayCollection
     */
    public function getMentionedGames()
    {
        return $this->mentionedGames;
    }

    /**
     * Add mentionedGames
     *
     * @param GameInterface $mentionedGames
     * @return NewsInterface
     */
    public function addMentionedGame(GameInterface $mentionedGames)
    {
        $this->mentionedGames[] = $mentionedGames;

        return $this;
    }

    /**
     * Remove mentionedGames
     *
     * @param GameInterface $mentionedGames
     */
    public function removeMentionedGame(GameInterface $mentionedGames)
    {
        $this->mentionedGames->removeElement($mentionedGames);
    }
}
