<?php

namespace My\UiBundle\Manager;

use Symfony\Component\HttpFoundation\Session;
use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Item;

class ScrapeManager
{
    private $torrentMan;
	private $session;
	private $tab;
	private $category;
	private $page;

    public function __construct(TorrentManager $torrentMan, Session $session)
    {
        $this->torrentMan = $torrentMan;
	    $this->session = $session;
    }


	public function run()
	{
		$this->setup();

		$infos = $this->scrape();

		$this->torrentMan->store($infos, $this->category, $this->page);

		return array('tab' => $this->tab, 'category' => $this->category, 'page' => $this->page);
	}


	public function setup()
	{
		$categories = array(
			ITEM::CATEGORY_SERIES_HD,
			ITEM::CATEGORY_MOVIES_HD,
			ITEM::CATEGORY_GAMES_PC,
			ITEM::CATEGORY_APPS_WIN,
			ITEM::CATEGORY_MUSIC,
			ITEM::CATEGORY_AUDIOBOOKS,
			ITEM::CATEGORY_OTHER,
		);

		$exit = 20;
		foreach ($categories as $category) {
			for ($p=0; $p<100; $p++) {
				$key = implode('.', array($category, $p));

				$lastUpdated = $this->torrentMan->findUpdateLast($category, $p);
//				die(var_dump($lastUpdated));
				die(var_dump(new \DateTime($lastUpdated)));

//				if (is_null($lastUpdated)) {
				if (new \DateTime($lastUpdated) == new \DateTime()) {
					$list = array($key => 1);
					break(2);
				}

				if (new \DateTime($lastUpdated) > new \DateTime('-1 hour')) {
					$exit--;
					if ($exit < 1) {
						throw new \Exception('finished');
					}
					continue;
				}

				$list[$key] = $this->torrentMan->findCategoryPagePopularity($category, $p);
				break;
			}
		}

		arsort($list);
		reset($list);
		die(var_dump($list));
		list($category, $p) = explode('.', key($list));
		$this->category = $category;
		$this->page = $p;

		if ($category == ITEM::CATEGORY_SERIES_HD) {
			$this->tab = 'series';
		} elseif ($category == ITEM::CATEGORY_MOVIES_HD) {
			$this->tab = 'movies';
		} elseif ($category == ITEM::CATEGORY_GAMES_PC) {
			$this->tab = 'games';
		} elseif ($category == ITEM::CATEGORY_APPS_WIN) {
			$this->tab = 'windows';
		} elseif ($category == ITEM::CATEGORY_MUSIC) {
			$this->tab = 'music';
		} elseif ($category == ITEM::CATEGORY_AUDIOBOOKS) {
			$this->tab = 'audiobooks';
		} elseif ($category == ITEM::CATEGORY_OTHER) {
			$this->tab = 'other';
		} else {
			throw new \Exception('unknown category');
		}
	}


	public function scrape()
	{
		$url = 'http://thepiratebay.se/browse/' . $this->category . '/' . $this->page . '/7';
		$html = file_get_contents($url);
//		die(var_dump($html));

		$table = substr($html, strpos($html, '<table id="searchResult">'));
		$table = substr($table, 0, strpos($table, '</table>'));
//		die(var_dump($table));

		$rows = explode('<tr>', $table);
		array_shift($rows);
		array_pop($rows);
//		die(var_dump($rows));

		$infos = array();
		foreach ($rows as $key => $row) {
//			die(var_dump($row));
			$columns = explode('<td', $row);
			//die(print_r($columns));

			// id
			$id = substr($columns[2], strpos($columns[2], 'href="/torrent/') + 15);
			$id = substr($id, 0, strpos($id, '/'));

			// title
			$title = substr($columns[2], strpos($columns[2], 'title="Details for ') + 19);
			$title = trim(substr($title, 0, strpos($title, '"')));
			//$title = str_replace(array('☆', '★'), '', $title);
			$title = preg_replace('/[^. _a-zA-z0-9\[\]()\-]/', '', $title);

			// size
			$size = substr($columns[2], strpos($columns[2], ', Size ') + 7);
			$size = substr($size, 0, strpos($size, ','));
			$size = str_replace('&nbsp;', ' ', $size);

			// uploader
			$uploader = substr($columns[2], strpos($columns[2], 'href="/user/') + 12);
			$uploader = substr($uploader, 0, strpos($uploader, '"'));
			$uploader = str_replace('/', '', $uploader);

			// linkMagnet
			$linkMagnet = substr($columns[2], strpos($columns[2], 'href="magnet') + 6);
			$linkMagnet = substr($linkMagnet, 0, strpos($linkMagnet, '"'));

			// demands
			$seeders = preg_replace('/[^0-9]/', '', $columns[3]);
			$leechers = preg_replace('/[^0-9]/', '', $columns[4]);

			$info = array(
				'id' => $id,
				'title' => $title,
				'size' => $size,
				'uploader' => $uploader,
				'linkMagnet' => $linkMagnet,
				'popularity' => $seeders + $leechers,
			);
//			die(var_dump($info));

			$infos[] = $info;
		}

//		die(var_dump($infos));
		return $infos;
	}
}
