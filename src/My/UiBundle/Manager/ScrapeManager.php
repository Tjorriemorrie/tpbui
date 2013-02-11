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

    /**
     * Run scraper
     */
    public function run($category, $page)
	{
		$infos = $this->scrape();

		$this->torrentMan->store($infos, $this->category, $this->page);

		return array('tab' => $this->tab, 'category' => $this->category, 'page' => $this->page);
	}

    /**
     * Scrape PirateBay
     */
    public function scrape($categoryCode)
	{
		$url = 'http://thepiratebay.se/browse/' . $this->category . '/' . $this->page . '/7';
		$html = file_get_contents($url);
//		die(var_dump($html));

		$table = substr($html, strpos($html, '<table id="searchResult">'));
		$table = substr($table, 0, strpos($table, '</table>'));
//		die(var_dump($table));

		$rows = explode('<tr>', $table);
		array_shift($rows);
		array_shift($rows);
		array_pop($rows);
//		die(var_dump($rows));

		$infos = array();
		foreach ($rows as $key => $row) {
//			die(print_r($row));
			$columns = explode('<td', $row);
//			die(print_r($columns));

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
