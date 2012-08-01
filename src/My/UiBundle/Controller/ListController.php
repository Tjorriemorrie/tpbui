<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListController extends Controller
{
	/**
	 * @Route("/homes", name="homes")
	 * @Template
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$torrents = $em->getRepository('MyUiBundle:Torrent')->findTop();
		
		return array();
	}
}
