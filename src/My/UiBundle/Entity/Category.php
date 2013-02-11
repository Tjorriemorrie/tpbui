<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\CategoryRepository")
 * @ORM\Table
 */
class Category
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Torrent", mappedBy="category")
     */
    protected $torrents;

	/**
	 * @ORM\Column(type="integer", unique=true)
	 */
	protected $code;

	/**
     * @ORM\Column(type="string", length=250)
     */
	protected $name;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $hd;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updatedAt;

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->torrents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code
     *
     * @param integer $code
     * @return Category
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
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
     * Set hd
     *
     * @param boolean $hd
     * @return Category
     */
    public function setHd($hd)
    {
        $this->hd = $hd;
    
        return $this;
    }

    /**
     * Get hd
     *
     * @return boolean 
     */
    public function getHd()
    {
        return $this->hd;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Category
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
     * @return Category
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
     * Add torrents
     *
     * @param \My\UiBundle\Entity\Torrent $torrents
     * @return Category
     */
    public function addTorrent(\My\UiBundle\Entity\Torrent $torrents)
    {
        $this->torrents[] = $torrents;
    
        return $this;
    }

    /**
     * Remove torrents
     *
     * @param \My\UiBundle\Entity\Torrent $torrents
     */
    public function removeTorrent(\My\UiBundle\Entity\Torrent $torrents)
    {
        $this->torrents->removeElement($torrents);
    }

    /**
     * Get torrents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTorrents()
    {
        return $this->torrents;
    }
}