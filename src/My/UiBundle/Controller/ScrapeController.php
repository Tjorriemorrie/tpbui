<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Utility\Scraper;
use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Category;

/**
 * @Route("/scrape")
 */
class ScrapeController extends Controller
{
    /**
     * @Route("/{category}/{page}", name="scrape_category_page")
     */
	public function scrapeAction(Category $category, $page)
	{
		try {
            /** @var $torrentMan TorrentManager */
            $torrentMan = $this->get('manager.torrent');
            $torrents = $torrentMan->findByCategoryAndPage($category, $page);

            if (count($torrents) !== 30 or $torrents[29]->getUpdatedAt() < new \DateTime('-8 hour')) {

                /** @var Scraper $scraper */
                $scraper = $this->get('utility.scraper');
                $infos = $scraper->run($category, $page);

                $torrentMan->saveInfos($infos, $category, $page);
                $torrents = $torrentMan->findByCategoryAndPage($category, $page);

            }

			$content['html'] = $this->renderView('MyUiBundle:Category:page.html.twig', array('page' => $page, 'torrents' => $torrents));
            $response = new JsonResponse($content);
		} catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode : 500;
            $response = new JsonResponse($e->getMessage(), $code);
		}

		return $response;
	}
}
