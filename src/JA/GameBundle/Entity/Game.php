<?php

namespace JA\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JA\GameBundle\Entity\GameRepository")
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="JA\GameBundle\Entity\GameSheet")
     * @ORM\JoinColumn(nullable=false)
     */
	private $gameSheet;
	
    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=20)
     */
    private $version;

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
     * @var string
     *
     * @ORM\Column(name="platforms", type="string", length=255)
	 * @Assert\MinLength(5)
     */
    private $platforms;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text")
	 * @Assert\MinLength(10)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="download", type="string", length=255, nullable=true)
     */
    private $download = null;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
	 * @Gedmo\Slug(fields={"title"})
     */
    private $slug;
	
	/**
     * @var Entity
     *
     * @ORM\ManyToMany(targetEntity="JA\NewsBundle\Entity\News", inversedBy="games", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=true)
     */
    private $news;


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
     * @return Game
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
     * Set version
     *
     * @param string $version
     * @return Game
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
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
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set platforms
     *
     * @param string $platforms
     * @return Game
     */
    public function setPlatforms($platforms)
    {
        $this->platforms = $platforms;
    
        return $this;
    }

    /**
     * Get platforms
     *
     * @return string 
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Game
     */
    public function setAbout($about)
    {
        $this->about = $about;
    
        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set download
     *
     * @param string $download
     * @return Game
     */
    public function setDownload($download)
    {
        $this->download = $download;
    
        return $this;
    }

    /**
     * Get download
     *
     * @return string 
     */
    public function getDownload()
    {
        return $this->download;
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
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set gameSheet
     *
     * @param \JA\GameBundle\Entity\GameSheet $gameSheet
     * @return GameSheet
     */
    public function setGameSheet(\JA\GameBundle\Entity\GameSheet $gameSheet)
    {
        $this->gameSheet = $gameSheet;
		
		return $this;
    }
	
	/**
     * Get gameSheet
     *
     * @return \JA\GameBundle\Entity\Gamesheet
     */
    public function getGameSheet()
    {
        return $this->gameSheet;
	}
    
    /**
     * Add news
     *
     * @param \JA\NewsBundle\Entity\News $news
     * @return Game
     */
    public function addNew(\JA\NewsBundle\Entity\News $news)
    {
        $this->news[] = $news;
    
        return $this;
    }

    /* Remove news
     *
     * @param \JA\NewsBundle\Entity\News $news
     */
    public function removeNew(\JA\NewsBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNews()
    {
        return $this->news;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
    }
}