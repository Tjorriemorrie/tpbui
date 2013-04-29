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
        $session = $this->get('session');
        $nav = $session->set('nav', 0);

        /** @var $categoryMan CategoryManager */
        $categoryMan = $this->get('manager.category');
        $categoryMan->loadCategories();

        return array();
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

        $last = $categoryMan->findLastViewed();

        $categoryAudio = $categoryMan->findBySection(Category::SECTION_AUDIO);
        $categoryVideo = $categoryMan->findBySection(Category::SECTION_VIDEO);
        $categoryApps = $categoryMan->findBySection(Category::SECTION_APPLICATION);
        $categoryGames = $categoryMan->findBySection(Category::SECTION_GAMES);
        $categoryPorn = $categoryMan->findBySection(Category::SECTION_PORN);
        $categoryOther = $categoryMan->findBySection(Category::SECTION_OTHER);

        //die(var_dump($id));

        return array('selected' => $nav, 'last' => $last,
                     'categoryAudio' => $categoryAudio, 'categoryVideo' => $categoryVideo,
                    'categoryApps' => $categoryApps, 'categoryGames' => $categoryGames,
                    'categoryPorn' => $categoryPorn, 'categoryOther' => $categoryOther);
    }
}
