<?php

namespace JA\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSheet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JA\GameBundle\Entity\GameSheetRepository")
 */
class GameSheet
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
     * @ORM\Column(name="developer", type="string", length=100)
     */
    private $developer;
	
	/**
     * @var string
	 *
     * @ORM\Column(name="projectName", type="string", length=100, unique=true)
     */
    private $projectName;


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
     * Set developer
     *
     * @param string $developer
     * @return GameSheet
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;
    
        return $this;
    }

    /**
     * Get developer
     *
     * @return string 
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * Get projectName
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     * @return GameSheet
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    
        return $this;
    }
}