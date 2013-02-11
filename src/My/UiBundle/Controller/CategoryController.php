<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Entity\Item;
use My\UiBundle\Manager\TorrentManager;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/{category}", name="category")
     * @Template()
     */
    public function indexAction($category)
    {
        /** @var $torrentMan TorrentManager */
        $torrentMan = $this->get('manager.torrent');

        return array('category' => $category);
    }

    /**
     * @Route("page", name="category_page")
     * @Template()
     */
    public function pageAction($page)
    {
        return array('page' => $page);
    }
}
