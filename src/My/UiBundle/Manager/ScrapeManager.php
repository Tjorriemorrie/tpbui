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
		$result = $this->setup();
		if (!$result) {
			return;
		}

		$infos = $this->scrape();
//		die(var_dump($infos));

		$this->torrentMan->store($infos, $this->category, $this->page);

		return array('tab' => $this->tab, 'category' => $this->category);
	}


	public function setup()
	{
//		$this->tab = 'series';
//		$this->category = Item::CATEGORY_SERIES_HD;
//		$this->page = 0;
//		return;
		$categories = array(
			ITEM::CATEGORY_SERIES_HD,
			ITEM::CATEGORY_MOVIES_HD,
			ITEM::CATEGORY_GAMES_PC,
		);

		$notfound = true;
		for ($p=0; $p<5; $p++) {
			$list = array();
			foreach ($categories as $category) {
				$list[$category] = $this->torrentMan->findUpdateLast($category, $p);
			}
//			die(var_dump($list));
			foreach ($list as $category => $date) {
				if (is_null($date) || new \DateTime($date) < new \DateTime('-1 hour')) {
					$this->category = $category;
					$this->page = $p;
					$notfound = false;
					break(2);
				}
			}
		}

		if ($category === ITEM::CATEGORY_SERIES_HD) {
			$this->tab = 'series';
		} elseif ($category === ITEM::CATEGORY_MOVIES_HD) {
			$this->tab = 'movies';
		} elseif ($category === ITEM::CATEGORY_GAMES_PC) {
			$this->tab = 'games';
		} elseif ($notfound) {
			return;
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
		foreach ($rows as $row) {
//			die(var_dump($row));
			$columns = explode('<td', $row);
			//die(print_r($columns));

			// id
			$id = substr($columns[2], strpos($columns[2], 'href="/torrent/') + 15);
			$id = substr($id, 0, strpos($id, '/'));

			// title
			$title = substr($columns[2], strpos($columns[2], 'title="Details for ') + 19);
			$title = trim(substr($title, 0, strpos($title, '"')));

			// size
			$size = substr($columns[2], strpos($columns[2], ', Size ') + 7);
			$size = substr($size, 0, strpos($size, ','));
			if (substr($size, -3) == 'MiB') {
				$size = (float)$size * 1000000;
			} elseif (substr($size, -3) == 'GiB') {
				$size = (float)$size * 1000000000;
			}

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

		return $infos;
	}
}