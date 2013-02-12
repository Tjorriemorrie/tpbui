<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Manager\TorrentManager;
use My\UiBundle\Entity\Torrent;

/**
 * @Route("/torrent")
 */
class TorrentController extends Controller
{
	/**
	 * @Route("/download/{torrent}", name="download")
	 */
	public function downloadAction(Torrent $torrent)
	{
        try {
            /* @var TorrentManager $torrentMan */
            $torrentMan = $this->get('manager.torrent');
            $torrentMan->setStatus($torrent, Torrent::STATUS_DOWNLOAD);
            $response = new JsonResponse('OK');
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode : 500;
            $response = new JsonResponse($e->getMessage(), $code);
        }

        return $response;
	}

	/**
	 * @Route("/unwanted/{torrent}", name="unwanted")
	 */
	public function unwantedAction(Torrent $torrent)
	{
        try {
            /* @var TorrentManager $torrentMan */
            $torrentMan = $this->get('manager.torrent');
            $torrentMan->setStatus($torrent, Torrent::STATUS_UNWANTED);
            $response = new JsonResponse('OK');
        } catch (\Exception $e) {
            $code = $e->getCode() ? $e->getCode : 500;
            $response = new JsonResponse($e->getMessage(), $code);
        }

		return $response;
	}
}
