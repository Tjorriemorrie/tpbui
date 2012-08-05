<?php

namespace My\UiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\UploaderRepository")
 * @ORM\Table(name="uploaders")
 * @ORM\HasLifecycleCallbacks
 */
class Uploader
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="string", length=250) */
	private $name;

	/** @ORM\OneToMany(targetEntity="Torrent", mappedBy="uploader") */
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
	
	/** Get Stats */
	public function getStatsUploaded($categoryId)
	{
		if (!$this->getTorrents()->count()) return null;
		
		$avg = array();
		foreach ($this->getTorrents() as $torrent) {
			if ($torrent->getCategory()->getId() != $categoryId) continue;
			$avg[] = $torrent->getPopularity();
		}
		
		if (!count($avg)) return null;
		return (array_sum($avg) / count($avg));
	}
	
	/** Get Stats Compared */
	public function getStatsUploadedCompared($avgStats, $categoryId)
	{
		$uploadedStats = $this->getStatsUploaded($categoryId);
		if (empty($avgStats) || is_null($uploadedStats)) return '-';
		return round($uploadedStats / $avgStats * 100) . '%'; 
	}
	
	public function getStatsDownloaded($categoryId)
	{
		if (!$this->getTorrents()->count()) return null;
		
		$countAll = $countDownloaded = 0;
		foreach ($this->getTorrents() as $torrent) {
			if ($torrent->getCategory()->getId() != $categoryId) continue;
			$countAll++;
			if (in_array($torrent->getStatus(), array(3, 5))) $countDownloaded++;
		}
		
		if ($countAll == 0) return '-';
		return round($countDownloaded / $countAll * 100) . '%';
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