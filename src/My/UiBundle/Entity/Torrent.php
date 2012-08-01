<?php

namespace My\UiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="My\UiBundle\Repository\TorrentRepository")
 * @ORM\Table(name="torrents")
 * @ORM\HasLifecycleCallbacks
 */
class Torrent
{
	const STATUS_BAD		= -2;
	const STATUS_UNWANTED	= -1;
	const STATUS_NORMAL		= 0;
	const STATUS_CANCELLED	= 1;
	const STATUS_DOWNLOAD	= 3;
	const STATUS_FINISHED	= 5;


	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="bigint")
	 */
	private $id;

	/** @ORM\ManyToOne(targetEntity="Category", inversedBy="torrents") */
	private $category;

	/** @ORM\Column(type="string", length=10) */
	private $size;

	/** @ORM\ManyToOne(targetEntity="Title", inversedBy="torrents") */
	private $title;

	/** @ORM\Column(type="text") */
	private $titleOriginal;

	/** @ORM\ManyToOne(targetEntity="Uploader", inversedBy="torrents") */
	private $uploader;

	/** @ORM\OneToMany(targetEntity="Demand", mappedBy="torrent", cascade={"remove"}) */
	private $demands;

	/** @ORM\Column(type="string", length=100) */
	private $linkTorrent;

	/** @ORM\Column(type="string", length=100) */
	private $linkMagnet;

	/** @ORM\Column(type="integer") */
	private $popularity;

	/** @ORM\Column(type="smallint") */
	private $status;


	/** @ORM\Column(type="boolean") */
	private $inspected;

	/** @ORM\ManyToOne(targetEntity="Pirate", inversedBy="torrents") */
	private $pirate;


	/** @ORM\OneToOne(targetEntity="Movie", inversedBy="torrent") */
	private $movie;

	/** @ORM\OneToOne(targetEntity="Show", inversedBy="torrent") */
	private $show;

	/** @ORM\OneToOne(targetEntity="Game", inversedBy="torrent") */
	private $game;


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
		$this->inspected = false;
		$this->demands = new \Doctrine\Common\Collections\ArrayCollection();
		$this->status = self::STATUS_NORMAL;
	}

	/** @ORM\PreUpdate */
	public function preUpdate()
	{
		$this->setModifiedAt(new \DateTime());
		$last = $this->getDemands()->last();
		$total = $last->getSeeders() + $last->getLeechers();
		$ratio = $total * $last->getSeeders() / ($last->getLeechers() > 0 ? $last->getLeechers() : 1);
		$this->setPopularity($total + $ratio);
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
     * Set titleOriginal
     *
     * @param string $titleOriginal
     */
    public function setTitleOriginal($titleOriginal)
    {
        $this->titleOriginal = $titleOriginal;
    }

    /**
     * Get titleOriginal
     *
     * @return string
     */
    public function getTitleOriginal()
    {
        return $this->titleOriginal;
    }

    /**
     * Set linkTorrent
     *
     * @param string $linkTorrent
     */
    public function setLinkTorrent($linkTorrent)
    {
        $this->linkTorrent = $linkTorrent;
    }

    /**
     * Get linkTorrent
     *
     * @return string
     */
    public function getLinkTorrent()
    {
        return $this->linkTorrent;
    }

    /**
     * Set linkMagnet
     *
     * @param string $linkMagnet
     */
    public function setLinkMagnet($linkMagnet)
    {
        $this->linkMagnet = $linkMagnet;
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
     * Set inspected
     *
     * @param boolean $inspected
     */
    public function setInspected($inspected)
    {
        $this->inspected = $inspected;
    }

    /**
     * Get inspected
     *
     * @return boolean
     */
    public function getInspected()
    {
        return $this->inspected;
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
     * Set category
     *
     * @param My\UiBundle\Entity\Category $category
     */
    public function setCategory(\My\UiBundle\Entity\Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return My\UiBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set title
     *
     * @param My\UiBundle\Entity\Title $title
     */
    public function setTitle(\My\UiBundle\Entity\Title $title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return My\UiBundle\Entity\Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set uploader
     *
     * @param My\UiBundle\Entity\Uploader $uploader
     */
    public function setUploader(\My\UiBundle\Entity\Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Get uploader
     *
     * @return My\UiBundle\Entity\Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * Add demands
     *
     * @param My\UiBundle\Entity\Demand $demands
     */
    public function addDemands(\My\UiBundle\Entity\Demand $demands)
    {
        $this->demands[] = $demands;
    }

    /**
     * Get demands
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getDemands()
    {
        return $this->demands;
    }

    /**
     * Set pirate
     *
     * @param My\UiBundle\Entity\Pirate $pirate
     */
    public function setPirate(\My\UiBundle\Entity\Pirate $pirate)
    {
        $this->pirate = $pirate;
    }

    /**
     * Get pirate
     *
     * @return My\UiBundle\Entity\Pirate
     */
    public function getPirate()
    {
        return $this->pirate;
    }

    /**
     * Set movie
     *
     * @param My\UiBundle\Entity\Movie $movie
     */
    public function setMovie(\My\UiBundle\Entity\Movie $movie)
    {
        $this->movie = $movie;
    }

    /**
     * Get movie
     *
     * @return My\UiBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set show
     *
     * @param My\UiBundle\Entity\Show $show
     */
    public function setShow(\My\UiBundle\Entity\Show $show)
    {
        $this->show = $show;
    }

    /**
     * Get show
     *
     * @return My\UiBundle\Entity\Show
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Set game
     *
     * @param My\UiBundle\Entity\Game $game
     */
    public function setGame(\My\UiBundle\Entity\Game $game)
    {
        $this->game = $game;
    }

    /**
     * Get game
     *
     * @return My\UiBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Add demands
     *
     * @param My\UiBundle\Entity\Demand $demands
     */
    public function addDemand(\My\UiBundle\Entity\Demand $demands)
    {
        $this->demands[] = $demands;
    }
}