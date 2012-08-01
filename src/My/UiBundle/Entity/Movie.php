<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="movies")
 * @ORM\HasLifecycleCallbacks
 */
class Movie
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\OneToOne(targetEntity="Torrent", mappedBy="movie") */
	private $torrent;
	
	/** 
	 * @Assert\NotBlank()
	 * @ORM\Column(type="string", length="10")
	 */
	private $quality;

	/**
	 * @Assert\NotBlank()
	 * @ORM\Column(type="string", length="20")
	 */
	private $source;
	
	/**
	 * @Assert\NotBlank()
	 * @ORM\Column(type="date")
	 */
	private $releasedAt;
	
	/** @ORM\Column(type="boolean") */
	private $extended;
	
	/** @ORM\Column(type="boolean") */
	private $uncut;
	
	/** @ORM\Column(type="boolean") */
	private $unrated;

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
		$this->uncut = false;
		$this->unrated = false;
		$this->extended = false;
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
     * Set source
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set releasedAt
     *
     * @param date $releasedAt
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;
    }

    /**
     * Get releasedAt
     *
     * @return date 
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * Set extended
     *
     * @param boolean $extended
     */
    public function setExtended($extended)
    {
        $this->extended = $extended;
    }

    /**
     * Get extended
     *
     * @return boolean 
     */
    public function getExtended()
    {
        return $this->extended;
    }

    /**
     * Set uncut
     *
     * @param boolean $uncut
     */
    public function setUncut($uncut)
    {
        $this->uncut = $uncut;
    }

    /**
     * Get uncut
     *
     * @return boolean 
     */
    public function getUncut()
    {
        return $this->uncut;
    }

    /**
     * Set unrated
     *
     * @param boolean $unrated
     */
    public function setUnrated($unrated)
    {
        $this->unrated = $unrated;
    }

    /**
     * Get unrated
     *
     * @return boolean 
     */
    public function getUnrated()
    {
        return $this->unrated;
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