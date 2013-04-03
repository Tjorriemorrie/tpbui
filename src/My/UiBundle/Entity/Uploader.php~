<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use My\UiBundle\Entity\Torrent;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\UploaderRepository")
 * @ORM\Table
 */
class Uploader
{
	/**
	 * @ORM\Column(type="bigint")
	 * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * @ORM\OneToMany(targetEntity="Torrent", mappedBy="uploader", fetch="EXTRA_LAZY")
     */
	protected $torrents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created_at;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updated_at;

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

    /**
     * Count wanted torrents
     */
    public function countWanted()
    {
        $count = 0;
        foreach ($this->getTorrents() as $torrent) {
            if ($torrent->isStatus(Torrent::STATUS_DOWNLOAD)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Count wanted torrents
     */
    public function countUnwanted()
    {
        $count = 0;
        foreach ($this->getTorrents() as $torrent) {
            if ($torrent->isStatus(Torrent::STATUS_UNWANTED)) {
                $count++;
            }
        }
        return $count;
    }

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

    /**
     * To string
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->torrents = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return Uploader
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Uploader
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Uploader
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add torrents
     *
     * @param \My\UiBundle\Entity\Torrent $torrents
     * @return Uploader
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
     * @return Torrent[]
     */
    public function getTorrents()
    {
        return $this->torrents;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Uploader
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
}
