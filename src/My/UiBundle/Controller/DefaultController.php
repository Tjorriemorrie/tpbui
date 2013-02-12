<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;

use My\UiBundle\Manager\CategoryManager;
use My\UiBundle\Entity\Category;

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
        /** @var $categoryMan CategoryManager */
        $categoryMan = $this->get('manager.category');
        $categories = $categoryMan->findAll();

        return array('categories' => $categories);
    }

    /**
     * @Route("/navigation", name="navigation", defaults={"id"=null})
     * @Template()
     */
    public function navigationAction()
    {
        $session = $this->get('session');
        $nav = $session->get('nav');

        /** @var $categoryMan CategoryManager */
        $categoryMan = $this->get('manager.category');
        $categories = $categoryMan->findAll();

        //die(var_dump($id));

        return array('selected' => $nav, 'categories' => $categories);
    }
}
