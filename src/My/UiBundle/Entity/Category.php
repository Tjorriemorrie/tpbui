<?php

namespace My\UiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="smallint") */
	private $code;

	/** @ORM\Column(type="string", length=20) */
	private $media;

	/** @ORM\Column(type="string", length=50) */
	private $name;

	/** @ORM\OneToMany(targetEntity="Torrent", mappedBy="category") */
	private $torrents;
	
	/** @ORM\Column(type="boolean") */
	private $scrape;
	
	/** @ORM\Column(type="integer") */
	private $pages;

	/** @ORM\Column(type="datetime") */
	private $createdAt;

	/** @ORM\Column(type="datetime", nullable=true) */
	private $modifiedAt;

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

	/** Construct */
	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->torrents = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/** @ORM\PreUpdate */
	public function preUpdate()
	{
		$this->setModifiedAt(new \DateTime());
	}
	
	/** Scrape */
	public function scrape($page)
	{
		
	}

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

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
     * @param smallint $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return smallint
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Add torrents
     *
     * @param My\UiBundle\Entity\Torrent $torrents
     */
    public function addTorrents(\My\UiBundle\Entity\Torrent $torrents)
    {
        $this->torrents[] = $torrents;
    }

    /**
     * Get torrents
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTorrents()
    {
        return $this->torrents;
    }

    /**
     * Set media
     *
     * @param string $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Get media
     *
     * @return string 
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set scrape
     *
     * @param boolean $scrape
     */
    public function setScrape($scrape)
    {
        $this->scrape = $scrape;
    }

    /**
     * Get scrape
     *
     * @return boolean 
     */
    public function getScrape()
    {
        return $this->scrape;
    }

    /**
     * Set pages
     *
     * @param integer $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * Get pages
     *
     * @return integer 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add torrents
     *
     * @param My\UiBundle\Entity\Torrent $torrents
     */
    public function addTorrent(\My\UiBundle\Entity\Torrent $torrents)
    {
        $this->torrents[] = $torrents;
    }
}