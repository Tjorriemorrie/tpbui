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

	/**
	 * @ORM\Column(type="smallint")
	 */
	private $page;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $title;

    /**
    * @ORM\Column(type="bigint")
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


	public function splitName()
	{
    	$title = str_replace(array('(', ')', '[', ']', 'NO RAR', 'no rars', '.avi', '.iso'), '', $this->getTitleOriginal());
    	$title = str_replace(array('.', '-'), ' ', $title);
    	$split = explode(' ', $title);

    	if (($key = array_search('1080p', $split)) !== false) $keywords['quality'] = '1080p';
    	elseif (($key = array_search('720p', $split)) !== false) $keywords['quality'] = '720p';
    	elseif (($key = array_search('720P', $split)) !== false) $keywords['quality'] = '720p';
    	elseif (($key = array_search('PPVRip', $split)) !== false) $keywords['quality'] = 'xvid';
    	elseif (($key = array_search('TS', $split)) !== false) $keywords['quality'] = 'xvid';
    	elseif (in_array($this->getCategory()->getCode(), array(205, 208))) $keywords['quality'] = 'xvid';
    	elseif (($key = array_search('DVDRiP', $split)) !== false) $keywords['quality'] = 'xvid';
    	else $keywords['quality'] = '<unknown>';

    	if (($key = array_search('PPVRip', $split)) !== false) $keywords['source'] = 'TS';
    	elseif (($key = array_search('TS', $split)) !== false) $keywords['source'] = 'TS';
    	elseif (($key = array_search('R5', $split)) !== false) $keywords['source'] = 'R5';
    	elseif (($key = array_search('R3', $split)) !== false) $keywords['source'] = 'R3';
    	elseif (($key = array_search('DVDRiP', $split)) !== false) $keywords['source'] = 'DVDRiP';
    	elseif (($key = array_search('BRRIP', $split)) !== false) $keywords['source'] = 'BRRIP';
    	elseif (($key = array_search('BRRip', $split)) !== false) $keywords['source'] = 'BRRip';
    	elseif (($key = array_search('BRrip', $split)) !== false) $keywords['source'] = 'BRRip';
    	elseif (($key = array_search('BrRip', $split)) !== false) $keywords['source'] = 'BRRip';
    	elseif (($key = array_search('BluRay', $split)) !== false) $keywords['source'] = 'BRRip';
    	elseif (($key = array_search('Bluray', $split)) !== false) $keywords['source'] = 'BRRip';
    	elseif (($key = array_search('BDRip', $split)) !== false) $keywords['source'] = 'BRRip';
    	else $keywords['source'] = '<unknown>';

    	$keywords['releasedAt'] = date('Y');
    	foreach ($split as $sp) if ($sp < date('Y', strtotime('+1 year')) && $sp > date('Y', strtotime('-10 years'))) $keywords['releasedAt'] = $sp;

    	while (empty($split[count($split)-1])) array_pop($split);
    	if (in_array($this->getCategory()->getCode(), array(205, 208)) && $split[count($split)-1] == 'eztv') array_pop($split);
    	if (in_array($this->getCategory()->getCode(), array(205, 208)) && $split[count($split)-1] == 'VTV') array_pop($split);
    	while (in_array($this->getCategory()->getCode(), array(401)) && (empty($split[count($split)-1]) || in_array($split[count($split)-1], array('org', 'BTARENA', 'tracker')))) array_pop($split);
    	$keywords['pirate'] = $split[count($split)-1];

    	if (in_array($this->getCategory()->getCode(), array(201, 207))) {
			$keywords['uncut'] = (stripos($this->getTitleOriginal(), 'uncut') !== false) ? true : false;
			$keywords['unrated'] = (stripos($this->getTitleOriginal(), 'unrated') !== false) ? true : false;
			$keywords['extended'] = (stripos($this->getTitleOriginal(), 'extended') !== false) ? true : false;
    	}

    	if (in_array($this->getCategory()->getCode(), array(205, 208))) {
    		if (strpos($title, 'Falling Skies') !== false) $title = substr($title, strpos($title, ' S')+2);
    		//die($title);
    		$keywords['season'] = round(substr($title, strpos($title, ' S')+2, 2));
    		$keywords['episode'] = round(substr($title, strpos($title, ' S')+5, 2));
    	}

		return $keywords;
	}

	/** Cut Name */
	public function cutName()
	{
		$title = $this->getTitleOriginal();
		if (strpos($title, '(') !== false) $title = substr($title, 0, strpos($title, '('));
		if (strpos($title, '{') !== false) $title = substr($title, 0, strpos($title, '{'));
		if (strpos($title, '-') !== false) $title = substr($title, 0, strpos($title, '-'));
		for ($year=date('Y', strtotime('-10 years')); $year<=date('Y', strtotime('+1 year')); $year++) {
			if (strpos($title, "$year") !== false) $title = substr($title, 0, strpos($title, "$year"));
		}
		if (stripos($title, 'brrip') !== false) $title = substr($title, 0, stripos($title, 'brrip'));
		if (stripos($title, '720p') !== false) $title = substr($title, 0, stripos($title, '720p'));
		if (stripos($title, '1080p') !== false) $title = substr($title, 0, stripos($title, '1080p'));
		if (stripos($title, 'H264') !== false) $title = substr($title, 0, stripos($title, 'H264'));

		if (stripos($title, 'extended') !== false) $title = substr($title, 0, stripos($title, 'extended'));
		if (stripos($title, 'uncut') !== false) $title = substr($title, 0, stripos($title, 'uncut'));
		if (stripos($title, 'unrated') !== false) $title = substr($title, 0, stripos($title, 'unrated'));

		if (stripos($title, 'HDTV') !== false) $title = substr($title, 0, stripos($title, 'HDTV'));

		$title = str_replace(array('.'), ' ', $title);

    	$title = trim($title);
    	return $title;
	}

	public function updateStatus($status)
	{
		if ($status == self::STATUS_DOWNLOAD) {
			$this->setStatus(self::STATUS_DOWNLOAD);
			$this->getTitle()->setStatus(self::STATUS_DOWNLOAD);
		} elseif ($status == self::STATUS_FINISHED) {
			$this->setStatus(self::STATUS_FINISHED);
			$this->getTitle()->setStatus(self::STATUS_FINISHED);
		} elseif ($status == self::STATUS_CANCELLED) {
			$this->setStatus(self::STATUS_CANCELLED);
			$this->getTitle()->setStatus(self::STATUS_CANCELLED);
		} elseif ($status == self::STATUS_UNWANTED) {
			$this->setStatus(self::STATUS_UNWANTED);
			$this->getTitle()->updateSize();
		} elseif ($status == self::STATUS_BAD) {
			$this->setStatus(self::STATUS_BAD);
			$this->getTitle()->updateSize();
		} else throw new \Exception('unknown status: ' . $status);
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
     * @param bigint $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return bigint 
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