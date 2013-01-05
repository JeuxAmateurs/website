<?php

namespace JA\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @var Entity
     *
     * @ORM\OneToMany(targetEntity="JA\NewsBundle\Entity\News", mappedBy="author", cascade={"persist"})
	 * @ORM\JoinColumn(nullable=true)
     */
    private $news;
	
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add news
     *
     * @param \JA\NewsBundle\Entity\News $news
     * @return User
     */
    public function addNew(\JA\NewsBundle\Entity\News $news)
    {
        $this->news[] = $news;
    
        return $this;
    }

    /**
     * Remove news
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
}