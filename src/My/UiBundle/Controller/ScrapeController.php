<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ScrapeController extends Controller
{
    /**
     * @Route("/scrapes", name="scrapes")
     * @Template()
     */
    public function indexAction()
    {
    	$session = $this->get('session');
    	if ($session->has('scrape')) $data = $session->get('scrape');
    	if (!isset($data) || empty($data)) return $this->redirect($this->generateUrl('defaultz'), 301);
    	//die(var_dump($data));

    	$em = $this->getDoctrine()->getEntityManager();
		$em->getRepository('MyUiBundle:Torrent')->scrape($data);

		$session->remove('scrape');
        return $this->redirect($this->generateUrl('inspect'), 301);
    }


    /**
     * @Route("/defaultz", name="defaultz")
     * @Template()
     */
    public function defaultzAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$em->getRepository('MyUiBundle:Category')->createDefaults();
    	$em->flush();
    	return $this->redirect($this->generateUrl('scrawl'), 301);
    }


    /**
     * @Route("/scrawl", name="scrawl")
     * @Template()
     */
    public function scrawlAction()
    {
		$em = $this->getDoctrine()->getEntityManager();

		// check for existing new torrents
		$uninspected = $em->getRepository('MyUiBundle:Torrent')->findByInspected(false);
		if (count($uninspected)) return $this->redirect($this->generateUrl('inspect'), 301);

		$data = $em->getRepository('MyUiBundle:Torrent')->scrawl();
    	//die(var_dump($data));

		$session = $this->get('session');
    	$session->set('scrape', $data);
    	return $this->redirect($this->generateUrl('scrape'), 301);
    }


    /**
     * @Route("/search/{id}", name="search")
     * @Template()
     */
    public function searchAction($id)
    {
		$em = $this->getDoctrine()->getEntityManager();
    	$title = $em->getRepository('MyUiBundle:Title')->find($id);
    	if (!$title) throw $this->createNotFoundException('No item found for id ' . $id);

		$data = $em->getRepository('MyUiBundle:Torrent')->search($title->getName());
    	if (!is_array($data)) return $this->redirect($this->generateUrl('home'));

    	$session = $this->get('session');
    	$session->set('scrape', $data);
    	return $this->redirect($this->generateUrl('scrape'), 301);
    }


    /**
     * @Route("/inspect", name="inspect")
     * @Template()
     */
    public function inspectAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
		$torrents = $em->getRepository('MyUiBundle:Torrent')->findByInspected(false);
		if (!$torrents) return $this->redirect($this->generateUrl('home'));
    	return array('torrents'=>$torrents);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @Template()
     */
    public function deleteAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if (!$torrent) throw $this->createNotFoundException('No item found for id ' . $id);

    	$em->remove($torrent);
    	$em->flush();
    	return $this->redirect($this->generateUrl('inspect'));
    }


    /**
     * @Route("/inspected/{id}", name="setinspected")
     * @Template()
     */
    public function setinspectedAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if (!$torrent) throw $this->createNotFoundException('No item found for id ' . $id);

    	//if ($torrent->getTitle()->getName() == $torrent->getTitleOriginal() && !is_null($torrent->getTitle()->getModifiedAt())) throw new \Exception('torrent does not have a title');
    	if (is_null($torrent->getPirate())) throw new \Exception('torrent does not have a pirate');
    	if ($torrent->getCategory()->getCode() == 207) if (is_null($torrent->getMovie())) throw new \Exception('torrent does not have movie details');
    	$torrent->setInspected(true);
    	$em->flush();
    	return $this->redirect($this->generateUrl('inspect'));
    }


    /**
     * @Route("/inspect/{id}", name="inspectone")
     * @Template()
     */
    public function inspectoneAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if (!$torrent) throw $this->createNotFoundException('No item found for id ' . $id);

    	if (is_null($movie = $torrent->getMovie())) {
    		$movie = new \My\UiBundle\Entity\Movie();
    		$keywords = $torrent->splitName();
	    	$movie->setQuality($keywords['quality']);
	    	$movie->setSource($keywords['source']);
	    	$movie->setReleasedAt($keywords['releasedAt']);
	    	$movie->setUncut($keywords['uncut']);
	    	$movie->setUnrated($keywords['unrated']);
	    	$movie->setExtended($keywords['extended']);
    	}
    	if ($movie->getReleasedAt() instanceof \DateTime) $movie->setReleasedAt($movie->getReleasedAt()->format('Y'));
    	$form = $this->createForm(new \My\UiBundle\Form\Type\MovieType(), $movie);

    	if ($request->getMethod() == 'POST') {
    		//die(var_dump($_POST));
    		$form->bindRequest($request);
    		$year = $_POST['movie']['releasedAt'];
    		$movie->setReleasedAt(new \DateTime($year . '-01-01 00:00:00'));
    		if ($form->isValid()) {
    			$em->persist($movie);
    			$torrent->setMovie($movie);
    			$em->flush();
    			return $this->redirect($this->generateUrl('inspect'));
    		}
    	}

    	return array('torrent'=>$torrent, 'form'=>$form->createView());
    }


    /**
     * @Route("/pirate/{id}", name="setpirate")
     * @Template()
     */
    public function setpirateAction(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if (!$torrent) throw $this->createNotFoundException('No item found for id ' . $id);

    	if (is_null($pirate = $torrent->getPirate())) {
    		$pirate = new \My\UiBundle\Entity\Pirate();
    		$keywords = $torrent->splitName();
	    	$pirate->setName($keywords['pirate']);
    	}
    	$form = $this->createForm(new \My\UiBundle\Form\Type\PirateType(), $pirate);

    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		if ($form->isValid()) {
	    		$existingPirate = $em->getRepository('MyUiBundle:Pirate')->findOneByName($pirate->getName());
	    		if (!$existingPirate) {
	    			$usePirate = $pirate;
	    			$em->persist($usePirate);
	    		} else $usePirate = $existingPirate;

    			$torrent->setPirate($usePirate);
    			$em->flush();
    			return $this->redirect($this->generateUrl('inspect'));
    		}
    	}

    	return array('torrent'=>$torrent, 'form'=>$form->createView());
    }


    /**
     * @Route("/scrape/test")
     * @Template()
     * Tests the scraper service
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('MyUiBundle:Category')->findAll();

        $scraper = $this->get('scraper');
        $url = 'http://www.thepiratebay.se/browse/' . $categories[0]->getCode() . '/0/7';
        $scraper->scrapePage($url);

        return array('categories'=>$categories);
    }
}
