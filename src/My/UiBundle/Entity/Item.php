<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\ItemRepository")
 * @ORM\Table(name="items")
 */
class Item
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="bigint")
	 */
	private $id;

	/**
	 * @ORM\Column(type="smallint")
	 */
	private $status;
	const STATUS_BAD		= -2;
	const STATUS_UNWANTED	= -1;
	const STATUS_NEW		= 0;
	const STATUS_DOWNLOAD	= 1;

	/**
     * @ORM\Column(type="smallint")
     */
	private $category;
	const CATEGORY_SERIES_HD = 208;
	const CATEGORY_MOVIES_HD = 207;
	const CATEGORY_GAMES_PC = 401;
	const CATEGORY_APPS_WIN = 301;
	const CATEGORY_MUSIC = 101;
	const CATEGORY_AUDIOBOOKS = 102;
	const CATEGORY_OTHER = 500;

	/**
	 * @ORM\Column(type="smallint")
	 */
	private $page;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $title;

    /**
    * @ORM\Column(type="string", length=25)
    */
    private $size;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $uploader;

	/**
	 * @ORM\Column(type="text")
	 */
    private $linkMagnet;

    /**
     * @ORM\Column(type="integer")
     */
	private $popularity;


	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $updatedAt;

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->status = self::STATUS_NEW;
	}


	/**
	 * Is New
	 */
	public function isNew()
	{
		return ($this->getStatus() === self::STATUS_NEW ? true : false);
	}


	/**
	 * Is Downloaded
	 */
	public function isDownloaded()
	{
		return ($this->getStatus() === self::STATUS_DOWNLOAD ? true : false);
	}


	/**
	 * Is Unwanted
	 */
	public function isUnwanted()
	{
		return ($this->getStatus() <= self::STATUS_UNWANTED ? true : false);
	}

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

    /**
     * Set id
     *
     * @param bigint $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return bigint 
     */
    public function getId()
    {
        return $this->id;
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
     * Set category
     *
     * @param smallint $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return smallint 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set page
     *
     * @param smallint $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return smallint 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     */
    public function setSize($size)
    {
        $this->size = $size;
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
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
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
     * @param text $linkMagnet
     */
    public function setLinkMagnet($linkMagnet)
    {
        $this->linkMagnet = $linkMagnet;
    }

    /**
     * Get linkMagnet
     *
     * @return text 
     */
    public function getLinkMagnet()
    {
        return $this->linkMagnet;
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
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}