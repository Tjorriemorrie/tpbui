<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="games")
 * @ORM\HasLifecycleCallbacks
 */
class Game
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\OneToOne(targetEntity="Torrent", mappedBy="game") */
	private $torrent;


	/**
	 * @Assert\NotBlank()
	 * @ORM\Column(type="date")
	 */
	private $releasedAt;


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
}