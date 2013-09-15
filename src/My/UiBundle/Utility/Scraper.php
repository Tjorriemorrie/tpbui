<?php

namespace My\UiBundle\Utility;

use My\UiBundle\Entity\Category;

class Scraper
{
    /**
     * Scrape PirateBay
     */
    public function run(Category $category, $page)
	{
        //die(var_dump(func_get_args()));
		$url = 'http://thepiratebay.sx/browse/' . $category->getCode() . '/' . --$page . '/7';
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

    /**
     * Scrape PirateBay
     * via XPath
     */
    public function runXpath(Category $category, $page)
	{
        libxml_use_internal_errors(true);
		$url = 'http://thepiratebay.is/browse/200/0/7';
        $html = file_get_contents($url);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $x = new \DOMXPath($dom);
        //$nodeList = $x->query('/html/body/div[2]/div[2]/table/tbody/tr');
        $nodeList = $x->query('//div[@class="detName"]');
        foreach ($nodeList as $node) {
            die(var_dump($node->textContent));
            die(var_dump($node));
        }

//		$html = file_get_contents($url);
////		die(var_dump($html));
//
//		$table = substr($html, strpos($html, '<table id="searchResult">'));
//		$table = substr($table, 0, strpos($table, '</table>'));
////		die(var_dump($table));
//
//		$rows = explode('<tr>', $table);
//		array_shift($rows);
//		array_shift($rows);
//		array_pop($rows);
////		die(var_dump($rows));
//
//		$infos = array();
//		foreach ($rows as $key => $row) {
////			die(print_r($row));
//			$columns = explode('<td', $row);
////			die(print_r($columns));
//
//			// id
//			$id = substr($columns[2], strpos($columns[2], 'href="/torrent/') + 15);
//			$id = substr($id, 0, strpos($id, '/'));
//
//			// title
//			$title = substr($columns[2], strpos($columns[2], 'title="Details for ') + 19);
//			$title = trim(substr($title, 0, strpos($title, '"')));
//			//$title = str_replace(array('☆', '★'), '', $title);
//			$title = preg_replace('/[^. _a-zA-z0-9\[\]()\-]/', '', $title);
//
//			// size
//			$size = substr($columns[2], strpos($columns[2], ', Size ') + 7);
//			$size = substr($size, 0, strpos($size, ','));
//			$size = str_replace('&nbsp;', ' ', $size);
//
//			// uploader
//			$uploader = substr($columns[2], strpos($columns[2], 'href="/user/') + 12);
//			$uploader = substr($uploader, 0, strpos($uploader, '"'));
//			$uploader = str_replace('/', '', $uploader);
//
//			// linkMagnet
//			$linkMagnet = substr($columns[2], strpos($columns[2], 'href="magnet') + 6);
//			$linkMagnet = substr($linkMagnet, 0, strpos($linkMagnet, '"'));
//
//			// demands
//			$seeders = preg_replace('/[^0-9]/', '', $columns[3]);
//			$leechers = preg_replace('/[^0-9]/', '', $columns[4]);
//
//			$info = array(
//				'id' => $id,
//				'title' => $title,
//				'size' => $size,
//				'uploader' => $uploader,
//				'linkMagnet' => $linkMagnet,
//				'popularity' => $seeders + $leechers,
//			);
////			die(var_dump($info));
//
//			$infos[] = $info;
//		}
//
////		die(var_dump($infos));
//		return $infos;
	}
}
