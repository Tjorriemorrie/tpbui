<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\TorrentRepository")
 * @ORM\Table
 */
class Torrent
{
	/**
	 * @ORM\Column(type="bigint")
	 * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
	 */
	protected $id;

	/**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="torrents")
     */
	protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Uploader", inversedBy="torrents")
     */
    protected $uploader;

    /**
	 * @ORM\Column(type="smallint")
	 */
	protected $status;
	const STATUS_UNWANTED	= -1;
	const STATUS_NEW		= 0;
	const STATUS_DOWNLOAD	= 1;

	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $page;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $title;

    /**
    * @ORM\Column(type="string", length=25)
    */
    protected $size;

	/**
	 * @ORM\Column(type="text")
	 */
    protected $linkMagnet;

    /**
     * @ORM\Column(type="integer")
     */
	protected $popularity;


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
     * is status
     * @return bool
     */
    public function isStatus($status)
    {
        return $this->getStatus() === (int)$status;
    }

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////


    /**
     * Set id
     *
     * @param integer $id
     * @return Torrent
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
     * Set status
     *
     * @param integer $status
     * @return Torrent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set page
     *
     * @param integer $page
     * @return Torrent
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Torrent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return Torrent
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set uploader
     *
     * @param string $uploader
     * @return Torrent
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;

        return $this;
    }

    /**
     * Get uploader
     *
     * @return string
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * Set linkMagnet
     *
     * @param string $linkMagnet
     * @return Torrent
     */
    public function setLinkMagnet($linkMagnet)
    {
        $this->linkMagnet = $linkMagnet;

        return $this;
    }

    /**
     * Get linkMagnet
     *
     * @return string
     */
    public function getLinkMagnet()
    {
        return $this->linkMagnet;
    }

    /**
     * Set popularity
     *
     * @param integer $popularity
     * @return Torrent
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Torrent
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
     * @return Torrent
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
     * Set category
     *
     * @param \My\UiBundle\Entity\Category $category
     * @return Torrent
     */
    public function setCategory(\My\UiBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \My\UiBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}