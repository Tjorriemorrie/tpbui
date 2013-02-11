<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use My\UiBundle\Manager\CategoryManager;
use My\UiBundle\Entity\Category;

use My\UiBundle\Manager\TorrentManager;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/{id}", name="category")
     * @Template()
     */
    public function indexAction(Category $category)
    {
        //die(var_dump($category));
        return array('category' => $category);
    }

    /**
     * @Route("/{category}/{page}", name="category_page")
     * @Template()
     */
    public function pageAction(Category $category, $page)
    {
        /** @var $torrentMan TorrentManager */
        $torrentMan = $this->get('manager.torrent');
        $torrents = $torrentMan->findByCategory($category);
        
        return array('page' => $page);
    }
}
