<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\TitleRepository")
 * @ORM\Table(name="titles")
 * @ORM\HasLifecycleCallbacks
 */
class Title
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="string", length=255, unique=true) */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity="Torrent", mappedBy="title")
	 * @ORM\OrderBy({"popularity"="DESC"})
	 */
	private $torrents;

	/** @ORM\Column(type="integer") */
	private $popularity;

	/** @ORM\Column(type="smallint") */
	private $size;

	/** @ORM\Column(type="smallint") */
	private $status;

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
		$this->popularity = 0;
		$this->size = 0;
		$this->status = 0;
		$this->torrents = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/** @ORM\PreUpdate */
	public function preUpdate()
	{
		$this->setModifiedAt(new \DateTime());
		$this->updatePopularity();
		$this->updateSize();
	}

	/** Sets title's popularity */
	public function updatePopularity()
	{
		$pop = 0;
		foreach ($this->getTorrents() as $torrent) {
			$last = $torrent->getDemands()->last();
			$total = $last->getSeeders() + $last->getLeechers();
			$ratio = $total * $last->getSeeders() / ($last->getLeechers() != 0 ? $last->getLeechers() : 1);
			$pop += $ratio + $total;
		}
		$this->setPopularity($pop);
	}

	/** Set title's size */
	public function updateSize()
	{
		$size = 0;
		foreach ($this->getTorrents() as $torrent) {
			if ($torrent->getStatus() >= 0) $size++;
		}
		$this->setSize($size);
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
     * Set popularity
     *
     * @param integer $popularity
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;
    }

    /**
     * Get popularity
     *
     * @return integer
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Set size
     *
     * @param smallint $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return smallint
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set status
     *
     * @param smallint $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return smallint
     */
    public function getStatus()
    {
        return $this->status;
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