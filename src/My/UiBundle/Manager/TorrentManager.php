<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use My\UiBundle\Repository\TorrentRepository;
use My\UiBundle\Manager\UploaderManager;
use My\UiBundle\Entity\Torrent;
use My\UiBundle\Entity\Category;

class TorrentManager
{
    protected $em;
    /** @var TorrentRepository */
	protected $repo;
    protected $uploadMan;

    /**
     * Construct
     */
    public function __construct(EntityManager $em, UploaderManager $uploadMan)
    {
        $this->em = $em;
        $this->repo = $em->getRepository('MyUiBundle:Torrent');
        $this->uploadMan = $uploadMan;
    }

	/**
	 * Stores scrapes info
	 */
	public function saveInfos($infos, Category $category, $page)
	{
		$this->repo->incrementPage($category, $page);

		foreach ($infos as $info) {
//			die(var_dump($info));
			$torrent = $this->find($info['id']);
			if ($torrent) {
				//$torrent->setUpdatedAt(new \DateTime());
                $torrent->setPage($page);
                $torrent->setPopularity($info['popularity']);
			} else {
				$torrent = $this->create($info, $category, $page);
			}
		}

		$this->em->flush();
	}


	/**
	 * Creates new torrent torrent
	 */
	public function create($info, Category $category, $page)
	{
		$torrent = new Torrent();
		$torrent->setId($info['id']);
		$torrent->setCategory($category);
		$torrent->setPage($page);
		$torrent->setTitle($info['title']);
		$torrent->setSize($info['size']);
		$torrent->setLinkMagnet($info['linkMagnet']);
		$torrent->setPopularity($info['popularity']);

        $torrent->setStatus(Torrent::STATUS_NEW);
        $torrent->setCreatedAt(new \DateTime());

        $uploader = $this->uploadMan->obtainUploader($info['uploader']);
		$torrent->setUploader($uploader);

		$this->em->persist($torrent);
        return $torrent;
	}

	/**
	 * Set status
	 */
	public function setStatus(Torrent $torrent, $status)
	{
        if ($torrent->getStatus() === $status) {
            $torrent->setStatus(Torrent::STATUS_NEW);
        } else {
		    $torrent->setStatus($status);
        }
        $torrent->setUpdatedAt(new \DateTime());

		$this->em->flush();
	}



	////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////

	/**
     * Find By Category
     * @return Torrent[]
     */
    public function findByCategoryAndPage(Category $category, $page)
    {
        return $this->repo->findByCategoryAndPage($category, $page);
    }

	public function findUpdateLast($category, $page)
	{
		return $this->repo->findUpdateLast($category, $page);
	}

	public function findCategoryPagePopularity($category, $page)
	{
		return $this->repo->findCategoryPagePopularity($category, $page);
	}

	public function find($torrentId)
	{
		return $this->repo->find($torrentId);
	}
}
