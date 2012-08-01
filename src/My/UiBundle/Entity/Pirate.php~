<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pirates")
 * @ORM\HasLifecycleCallbacks
 */
class Pirate
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="string", length=250, unique=true) */
	private $name;

	/** @ORM\OneToMany(targetEntity="Torrent", mappedBy="pirate") */
	private $torrents;

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
     * Add torrents
     *
     * @param My\UiBundle\Entity\Torrent $torrents
     */
    public function addTorrent(\My\UiBundle\Entity\Torrent $torrents)
    {
        $this->torrents[] = $torrents;
    }
}