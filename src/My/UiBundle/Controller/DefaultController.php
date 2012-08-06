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

	    $series = $torrentMan->findByCategory(Item::CATEGORY_SERIES_HD);
//	    die(var_dump($series));

	    $movies = $torrentMan->findByCategory(Item::CATEGORY_MOVIES_HD);
//	    die(var_dump($movies));

	    $games = $torrentMan->findByCategory(Item::CATEGORY_GAMES_PC);
//	    die(var_dump($movies));

	    return array('series' => $series, 'movies' => $movies, 'games' => $games);
    }


	/**
	 * @Route("/scrape", name="scrape")
	 */
	public function scrapeAction()
	{
		$scrapeMan = $this->get('manager.scrape');

		$content = $scrapeMan->run();
		if (!is_null($content)) {
			$html = $this->torrentMan->findByCategory($content['category']);
		}

		$response = new Response(json_encode($content));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}
