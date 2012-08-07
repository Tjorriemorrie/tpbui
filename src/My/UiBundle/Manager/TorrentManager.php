<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use My\UiBundle\Entity\Item;

class TorrentManager
{
    private $em;
	/**
	 * @var \My\UiBundle\Repository\ItemRepository
	 */
	private $repo;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository('MyUiBundle:Item');
    }


	/**
	 * Stores scrapes info
	 */
	public function store($infos, $category, $page)
	{
		$this->repo->incrementPage($category, $page);

		foreach ($infos as $info) {
//			die(var_dump($info));
			$item = $this->find($info['id']);
			if ($item) {
				$item->setPage($page);
				$item->setPopularity($info['popularity']);
				$item->setUpdatedAt(new \DateTime());
			} else {
				$this->create($info, $category, $page);
			}
		}

		$this->em->flush();
	}


	/**
	 * Creates new torrent item
	 */
	public function create($info, $category, $page)
	{
		$item = new Item();
		$item->setId($info['id']);
		$item->setCategory($category);
		$item->setPage($page);
		$item->setTitle($info['title']);
		$item->setSize(round($info['size']));
		$item->setUploader($info['uploader']);
		$item->setLinkMagnet($info['linkMagnet']);
		$item->setPopularity($info['popularity']);

		$this->em->persist($item);
	}


	/**
	 * Set status
	 */
	public function setStatus($id, $status)
	{
		$item = $this->find($id);
		$item->setStatus($status);
		$this->em->flush();

		$content['category'] = $item->getCategory();
		if ($item->getCategory() === ITEM::CATEGORY_SERIES_HD) {
			$content['tab'] = 'series';
		} elseif ($item->getCategory() === ITEM::CATEGORY_MOVIES_HD) {
			$content['tab'] = 'movies';
		} elseif ($item->getCategory() === ITEM::CATEGORY_GAMES_PC) {
			$content['tab'] = 'games';
		} elseif ($item->getCategory() === ITEM::CATEGORY_APPS_WIN) {
			$content['tab'] = 'windows';
		} elseif ($item->getCategory() === ITEM::CATEGORY_MUSIC) {
			$content['tab'] = 'music';
		} elseif ($item->getCategory() === ITEM::CATEGORY_AUDIOBOOKS) {
			$content['tab'] = 'audiobooks';
		} else {
			throw new \Exception('unknown category');
		}

		return $content;
	}



	////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////

	/**
     * Find By
     */
    public function findByCategory($categoryCode)
    {
        return $this->repo->findByCategory($categoryCode);
    }

	public function findUpdateLast($category, $page)
	{
		return $this->repo->findUpdateLast($category, $page);
	}

	public function findCategoryPagePopularity($category, $page)
	{
		return $this->repo->findCategoryPagePopularity($category, $page);
	}

	public function find($itemId)
	{
		return $this->repo->find($itemId);
	}
}