<?php

namespace My\UiBundle\Service;

/**
 * Scraper
 */
class Scraper
{
	private $_em;
	public function setEm($em)
	{
		$this->_em = $em;
	}
	
	
	/**
	 * Processes scraped data into torrents
	 */
	public function process($data, $em)
	{
		foreach ($data as $item) {
			$torrent = $em->getRepository('MyUiBundle:Torrent')->find($item['id']);
			if (!$torrent) {
				$torrent = new \My\UiBundle\Entity\Torrent();
				$torrent->setId($item['id']);

				// do not want to overwrite custom title
				try {
					$title = $em->getRepository('MyUiBundle:Title')->findOneByName($item['title']);
				} catch (\Exception $e) {
					$title = null;
				}
				if (!$title) {
					$title = new \My\UiBundle\Entity\Title();
					$title->setName($item['title']);
					$em->persist($title);
				}
				$torrent->setTitle($title);
				$torrent->setTitleOriginal($item['title']);
			}

			// these should not have an effect
			$torrent->setSize($item['size']);
			$torrent->setLinkTorrent($item['linkTorrent']);
			$torrent->setLinkMagnet($item['linkMagnet']);

			$category = $em->getRepository('MyUiBundle:Category')->findOneByCode($item['category']);
			if (!$category) throw new \Exception('no category for code: ' . $item['category']);
			$torrent->setCategory($category);

			$uploader = $em->getRepository('MyUiBundle:Uploader')->findOneByName($item['uploader']);
			if (!$uploader) {
				$uploader = new \My\UiBundle\Entity\Uploader();
				$uploader->setName($item['uploader']);
				$em->persist($uploader);
			}
			$torrent->setUploader($uploader);
			$em->persist($torrent);

			$demand = $em->getRepository('MyUiBundle:Demand')->findOneBy(array('day'=>date('Y-m-d'), 'torrent'=>$item['id']));
			if (!$demand) {
				$demand = new \My\UiBundle\Entity\Demand();
				$demand->setDay(new \DateTime());
				$demand->setTorrent($torrent);
				$em->persist($demand);
			}
			$demand->setSeeders($item['seeders']);
			$demand->setLeechers($item['leechers']);
			$torrent->setPopularity($item['seeders'] + $item['leechers']);
			$torrent->getTitle()->updatePopularity();

			$em->flush();
		}
	}
	
	
	/**
	 * Scrapes page from TPB
	 */
	public function scrapePage($url)
	{
        $page = $this->curlPage($url);
        //$page = file_get_contents($url);
		//die($page);

        $data = $this->splitPage($page);
		//die(var_dump($data));

		return $data;
	}


    /**
     * Uses curl instead of default file_get_contents
     */
    private function curlPage($url)
    {
        if (!function_exists('curl_init')) throw new \Exception('Sorry cURL is not installed!');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, 'http://thepiratebay.se/browse');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HEADER, false);
            //curl_setopt($ch, CURLOPT_POST, false);
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_HTTP200ALIASES, (array)400);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $tries = 0;
        $log = array();
        do {
            $tries++;
            if ($tries > 5) throw new \Exception('failed after 5 attempts: ' . $url . '<br>' . implode('<br>', $log));
            $page = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);
            if ($curl_errno > 0) $log[] = $curl_error;
            //die(var_dump(curl_getinfo($ch)));
            //die($curl_output);
        } while ($curl_errno > 0);

        return $page;
    }


    /**
     * Splits the page into torrent rows with information
     * @param $page
     * @return array
     */
    private function splitPage($page)
    {
        $rows = explode('<td class="vertTh">', $page);
        array_shift($rows);

        //die(var_dump($rows));
        //die(print_r($rows[0]));

        $data = array();
        foreach ($rows as $row) {
            $item = array('string'=>$row);

            // category
            $item['category'] = substr($row, strrpos($row, 'href="/browse/')+14, 3);

            $row = substr($row, strpos($row, '<div class="detName'));

            // id
            $id = substr($row, strpos($row, '<a href="/torrent/')+18);
            $id = substr($id, 0, strpos($id, '/'));
            $item['id'] = $id;

            // title
            $title = substr($row, strpos($row, '>')+1);
            $title = substr($title, strpos($title, '>')+1);
            $title = substr($title, 0, strpos($title, '<'));
            $item['title'] = $title;

            // linkTorrent
            $linkTorrent = substr($row, strpos($row, 'href="http://torrents.thepiratebay.se')+6);
            $linkTorrent = substr($linkTorrent, 0, strpos($linkTorrent, '"'));
            $item['linkTorrent'] = $linkTorrent;

            // linkMagnet
            $linkMagnet = substr($row, strpos($row, 'href="magnet')+6);
            $linkMagnet = substr($linkMagnet, 0, strpos($linkMagnet, '"'));
            $item['linkMagnet'] = $linkMagnet;

            // uploader
            $uploader = substr($row, strpos($row, 'href="/user/')+12);
            $uploader = substr($uploader, 0, strpos($uploader, '"'));
            $uploader = str_replace('/', '', $uploader);
            $item['uploader'] = $uploader;

            // size
            $size = substr($row, strpos($row, ', Size ')+7);
            $size = substr($size, 0, strpos($size, ','));
            $size = str_replace('&nbsp;', ' ', $size);
            $item['size'] = $size;

            // seeders
            $seeders = substr($row, strpos($row, '<td align="right">')+18);
            $seeders = substr($seeders, 0, strpos($seeders, '<'));
            $item['seeders'] = $seeders;

            // leechers
            $leechers = substr($row, strrpos($row, '<td align="right">')+18);
            $leechers = substr($leechers, 0, strpos($leechers, '<'));
            $item['leechers'] = $leechers;

            unset($item['string']);
            $data[] = $item;
        }

        return $data;
    }
}