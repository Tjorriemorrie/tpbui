<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="demands")
 * @ORM\HasLifecycleCallbacks
 */
class Demand
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="date") */
	private $day;

	/** @ORM\ManyToOne(targetEntity="Torrent", inversedBy="demands") */
	private $torrent;

	/** @ORM\Column(type="smallint") */
	private $seeders;

	/** @ORM\Column(type="smallint") */
	private $leechers;

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
     * Set day
     *
     * @param datetime $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * Get day
     *
     * @return datetime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set seeders
     *
     * @param smallint $seeders
     */
    public function setSeeders($seeders)
    {
        $this->seeders = $seeders;
    }

    /**
     * Get seeders
     *
     * @return smallint
     */
    public function getSeeders()
    {
        return $this->seeders;
    }

    /**
     * Set leechers
     *
     * @param smallint $leechers
     */
    public function setLeechers($leechers)
    {
        $this->leechers = $leechers;
    }

    /**
     * Get leechers
     *
     * @return smallint
     */
    public function getLeechers()
    {
        return $this->leechers;
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
     * Set torrent
     *
     * @param My\UiBundle\Entity\Torrent $torrent
     */
    public function setTorrent(\My\UiBundle\Entity\Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * Get torrent
     *
     * @return My\UiBundle\Entity\Torrent
     */
    public function getTorrent()
    {
        return $this->torrent;
    }
}