<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Manager\ScrapeManager;
use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Torrent;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
	    return array();
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
}
