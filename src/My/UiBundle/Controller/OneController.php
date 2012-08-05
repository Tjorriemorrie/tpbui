<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

/**
 * @var $em Doctrine\ORM\EntityManager
 */
class OneController extends Controller
{
    /**
     * @Route("/one", name="one")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$size = $em->getRepository('MyUiBundle:Torrent')->getSize();
    	if ($size > 10000) return $this->redirect('/clean');
    	
    	$categories = $em->getRepository('MyUiBundle:Category')->findBy(array('scrape'=>1));
    	
    	return array('categories'=>$categories);
    }
    
    
    /**
     * @Route("/load/category/{id}", name="loadcategory")
     * @Template()
     */
    public function loadcategoryAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$torrents = $em->getRepository('MyUiBundle:Torrent')->getByCategory($id);
    	
    	$avgStats = $em->getRepository('MyUiBundle:Uploader')->getAverageStats($id);
    	//die(var_dump($avgStats));
    	
    	return array('torrents'=>$torrents, 'avgStats'=>$avgStats, 'categoryId'=>$id);
    }
    
    
    /**
     * @Route("/scrape/category/{id}", name="scrapecategory")
     */
    public function scrapecategoryAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$category = $em->getRepository('MyUiBundle:Category')->find($id);
    	
    	$session = $this->get('session');
    	$scrapes = unserialize($session->get('scrapes', ''));
    	if (!isset($scrapes[$id])) $scrapes[$id] = 0;
    	
    	$scraper = $this->get('scraper');
		$url = 'http://thepiratebay.org/browse/' . $category->getCode() . '/' . $scrapes[$id] . '/7';
    	$data = $scraper->scrapePage($url);
    	$scraper->process($data, $em);

    	$scrapes[$id]++;
    	$session->set('scrapes', serialize($scrapes));
    	
    	return new Response(json_encode(array(
    		'msg'	=> 'Successfully scraped ' . $category->getName() . ' page ' . $scrapes[$id],
    		'text'	=> $category->getName() . ' (' . $scrapes[$id] . ')',
    	)));
    }
    
    
    /**
     * @Route("/load/busy", name="loadbusy")
     * @Template()
     */
    public function loadbusyAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	 
  	   	$torrents = $em->getRepository('MyUiBundle:Torrent')->getBusy();
    	
    	return array('torrents'=>$torrents);
    }
    
    
    /**
     * @Route("/load/done", name="loaddone")
     * @Template()
     */
    public function loaddoneAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$torrents = $em->getRepository('MyUiBundle:Torrent')->getDone();
    	
    	return array('torrents'=>$torrents);
    }
    
    
    /**
     * @Route("/load/bad", name="loadbad")
     * @Template()
     */
    public function loadbadAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$torrents = $em->getRepository('MyUiBundle:Torrent')->getBad();
    	
    	return array('torrents'=>$torrents);
    }
    
    
    /**
     * @Route("/torrent/busy/{id}", name="torrentbusy")
     */
    public function torrentbusyAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	$torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_DOWNLOAD);
    	$em->flush();
    	die('OK');
    }

    
    /**
     * @Route("/status/{status}/{id}", name="status")
     */
    public function statusAction($status, $id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if ($status == 'done') $torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_FINISHED);
    	elseif ($status == 'cancelled') $torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_CANCELLED);
    	elseif ($status == 'unwanted') $torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_UNWANTED);
    	elseif ($status == 'nomral') $torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_NORMAL);
    	elseif ($status == 'bad') $torrent->setStatus(\My\UiBundle\Entity\Torrent::STATUS_BAD);
    	else die('FAIL');
    	$em->flush();
    	die('OK');
    }
    
    
    /**
     * @Route("/similar/{id}", name="similar")
     * @Template()
     */
    public function similarAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	
    	$uploaders = $em->getRepository('MyUiBundle:Uploader')->getNames();
    	//die(var_dump($uploaders));
    	
    	$search = $this->get('search');
    	$response = $search->search($torrent->getTitleOriginal(), $torrent->getCategory()->getName(), $uploaders);
    	
    	$similars = array();

    	//die(var_dump($search->getNumDocs()));
    	//die(var_dump($hits));
    	//die('hits'.count($hits));
    	
    	if (count($response['hits'])) foreach ($response['hits'] as $hit) {
			if ($hit->unique == $id) continue;
			elseif ($hit->category != $torrent->getCategory()->getName()) continue;
			else {
				//die($hit->category . ' is not the same as ' . $torrent->getCategory()->getName());
	    		$torrentHit = $em->getRepository('MyUiBundle:Torrent')->find($hit->unique);
	    		if (in_array($torrentHit->getStatus(), array(3, 5))) $class = 'green';
	    		elseif (in_array($torrentHit->getStatus(), array(-1, -2))) $class = 'red';
	    		else $class = 'blue';
				$similars[] = '<p class="' . $class . '">' . str_pad(round($hit->score * 100), 2, 0, STR_PAD_LEFT) . '% ' . $hit->title . '</p>'; 
			}
	    	if (count($similars) >= 5) break;
    	}

    	return array('similars'=>$similars, 'q'=>$response['q']);

    }
    
    
    /**
     * @Route("/clean", name="clean")
     */
    public function cleanAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrents = $em->getRepository('MyUiBundle:Torrent')->getOld();
    	 
    	if (count($torrents)) foreach ($torrents as $torrent) {
    		$em->remove($torrent);
    	}
    	
    	$em->flush();
    	
    	return $this->redirect('/one');
    }
    
    
    /**
     * @Route("/reindex", name="reindex")
     * @Template()
     */
    public function reindexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrents = $em->getRepository('MyUiBundle:Torrent')->getOldest(100);
    	 
    	foreach ($torrents as $key => $torrent) {
    		$torrent->setPopularity($torrent->getPopularity() + 1);
    	}
    	
    	$em->flush();

    	$search = $this->get('search');
    	$search->optimize();
    	die(var_dump($search->getNumDocs()));
    }
}

