<?php

namespace My\UiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$query = $em->createQueryBuilder()
    		->select('t')->from('My\UiBundle\Entity\Title', 't')
    		->where('t.size >= ?1')->setParameter(1, 1)
    		->andWhere('t.status >= ?3')->setParameter(3, 0)
    		->andWhere('t.status <= ?2')->setParameter(2, 1)
    		->orderBy('t.popularity', 'DESC')
    		->getQuery();
    	$titles = $query->getResult();

    	$query = $em->createQueryBuilder()
    		->select('t')->from('My\UiBundle\Entity\Title', 't')
    		->where('t.size >= ?1')->setParameter(1, 0)
    		->andWhere('t.status = ?2')->setParameter(2, 3)
    		->orderBy('t.id', 'ASC')
    		->getQuery();
    	$queue = $query->getResult();

    	$query = $em->createQueryBuilder()
    		->select('t')->from('My\UiBundle\Entity\Title', 't')
    		->where('t.size >= ?1')->setParameter(1, 0)
    		->andWhere('t.status = ?2')->setParameter(2, 5)
    		->orderBy('t.popularity', 'DESC')
    		->getQuery();
    	$finished = $query->getResult();

    	return array('titles'=>$titles, 'queue'=>$queue, 'finished'=>$finished);
    }


    /**
     * @Route("/action/{do}/{id}", name="action")
     */
    public function doAction($do, $id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$torrent = $em->getRepository('MyUiBundle:Torrent')->find($id);
    	if (!$torrent) throw $this->createNotFoundException('No item found for id ' . $id);

    	if (in_array($do, array('torrent', 'magnet'))) {
    		$torrent->updateStatus($torrent::STATUS_DOWNLOAD);
    		$em->flush();
    		if ($do == 'torrent') return $this->redirect($torrent->getlinkTorrent());
    		if ($do == 'magnet') return $this->redirect($torrent->getlinkMagnet());
    	}
    	elseif (in_array($do, array('finished', 'cancelled'))) {
    		if ($do == 'finished') $torrent->updateStatus($torrent::STATUS_FINISHED);
    		if ($do == 'cancelled') $torrent->updateStatus($torrent::STATUS_CANCELLED);
    		$em->flush();
    		return $this->redirect($this->generateUrl('home'));
    	}
    	elseif (in_array($do, array('bad', 'unwanted'))) {
    		if ($do == 'bad') $torrent->updateStatus($torrent::STATUS_BAD);
    		if ($do == 'unwanted') $torrent->updateStatus($torrent::STATUS_UNWANTED);
    		$em->flush();
    		return $this->redirect($this->generateUrl('home'));
    	} else throw $this->createNotFoundException('No do found for ' . $do);
    }
}
