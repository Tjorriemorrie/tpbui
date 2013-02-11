<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Manager\ScrapeManager;
use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Item;


/**
 * @var $torrentMan TorrentManager
 * @property $scrapeMan ScrapeManager
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $torrentMan = $this->get('manager.torrent');

	    $movies = $torrentMan->findByCategory(Item::CATEGORY_MOVIES_SD);
	    $games = $torrentMan->findByCategory(Item::CATEGORY_GAMES_PC);
	    $windows = $torrentMan->findByCategory(Item::CATEGORY_APPS_WIN);
	    $music = $torrentMan->findByCategory(Item::CATEGORY_MUSIC);
	    $audiobooks = $torrentMan->findByCategory(Item::CATEGORY_AUDIOBOOKS);
	    $other = $torrentMan->findByCategory(Item::CATEGORY_OTHER);

	    return array('movies' => $movies, 'games' => $games, 'windows' => $windows, 'music' => $music, 'audiobooks' => $audiobooks, 'other' => $other);
    }


	/**
	 * @Route("/downloaded", name="downloaded")
	 */
	public function downloadedAction()
	{
		$id = $this->getRequest()->query->get('id');
		$torrentMan = $this->get('manager.torrent');

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		try {
			$content = $torrentMan->setStatus($id, Item::STATUS_DOWNLOAD);
			$items = $torrentMan->findByCategory($content['category']);
			$content['html'] = $this->renderView('MyUiBundle:Default:columns.html.twig', array('items' => $items));
		} catch (\Exception $e) {
			$content = $e->getMessage();
			$response->setStatusCode(400);
		}

		$response->setContent(json_encode($content));
		return $response;
	}


	/**
	 * @Route("/unwanted", name="unwanted")
	 */
	public function unwantedAction()
	{
		/* @var TorrentManager $torrentMan */
		$torrentMan = $this->get('manager.torrent');
		$id = $this->getRequest()->query->get('id');

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		try {
			$content = $torrentMan->setStatus($id, Item::STATUS_UNWANTED);
			$items = $torrentMan->findByCategory($content['category']);
			$content['html'] = $this->renderView('MyUiBundle:Default:columns.html.twig', array('items' => $items));
		} catch (\Exception $e) {
			$content = $e->getMessage();
			$response->setStatusCode(400);
		}

		$response->setContent(json_encode($content));
		return $response;
	}


	/**
	 * @Route("/scrape", name="scrape")
	 */
	public function scrapeAction()
	{
		set_time_limit(0);
		$scrapeMan = $this->get('manager.scrape');
		$torrentMan = $this->get('manager.torrent');

		$response = new Response();
		$response->headers->set('Content-Type', 'application/json');
		try {
			$content = $scrapeMan->run();
			$items = $torrentMan->findByCategory($content['category']);
			$content['html'] = $this->renderView('MyUiBundle:Default:columns.html.twig', array('items' => $items));
		} catch (\Exception $e) {
			$content = $e->getMessage();
			$response->setStatusCode(400);
		}

		$response->setContent(json_encode($content));
		return $response;
	}
}
