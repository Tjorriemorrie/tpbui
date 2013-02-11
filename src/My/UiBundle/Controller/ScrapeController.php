<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Manager\ScrapeManager;
use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Item;

/**
 * @Route("/scrape")
 */
class ScrapeController extends Controller
{
    /**
     * @Route("/{category}/{page}", name="scrape_category_page")
     */
	public function scrapeAction($category, $page)
	{
		try {
            /** @var $scrapeMan ScrapeManager */
            $scrapeMan = $this->get('manager.scrape');
            /** @var $torrentMan TorrentManager */
            $torrentMan = $this->get('manager.torrent');

			$content = $scrapeMan->run($category, $page);
			$items = $torrentMan->findByCategory($content['category']);

			$content['html'] = $this->renderView('MyUiBundle:Default:columns.html.twig', array('items' => $items));
            $response = new JsonResponse($content);
		} catch (\Exception $e) {
            $response = new JsonResponse($e->getMessage(), $e->getCode());
		}

		return $response;
	}
}
