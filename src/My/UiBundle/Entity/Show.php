<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="shows")
 * @ORM\HasLifecycleCallbacks
 */
class Show
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\OneToOne(targetEntity="Torrent", mappedBy="show") */
	private $torrent;

	/**
	 * @Assert\NotBlank()
	 * @ORM\Column(type="string", length="10")
	 */
	private $quality;

	/**
	 * @Assert\NotBlank()
	 * @ORM\Column(type="string", length="255")
	 */
	private $series;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $season;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	private $episode;


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
     * Set quality
     *
     * @param string $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * Get quality
     *
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set series
     *
     * @param string $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

    /**
     * Get series
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set season
     *
     * @param smallint $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    }

    /**
     * Get season
     *
     * @return smallint
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set episode
     *
     * @param smallint $episode
     */
    public function setEpisode($episode)
    {
        $this->episode = $episode;
    }

    /**
     * Get episode
     *
     * @return smallint
     */
    public function getEpisode()
    {
        return $this->episode;
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