<?php

namespace My\UiBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Lucene Search
 */
class SearchListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
    	$entity = $args->getEntity();
    	$em = $args->getEntityManager();
    	
    	if ($entity instanceof \My\UiBundle\Entity\Torrent) {
    		$search = new \My\UiBundle\Service\Search();
    		$search->addTorrent($entity);
    	}    	
    }

    	
    public function postRemove(LifecycleEventArgs $args)
    {
    	$entity = $args->getEntity();
    	$em = $args->getEntityManager();
    	
    	if ($entity instanceof \My\UiBundle\Entity\Torrent) {
    		$search = new \My\UiBundle\Service\Search();
    		$search->removeTorrent($entity);
    	}    	
    }

    	
    public function postUpdate(LifecycleEventArgs $args)
    {
    	$entity = $args->getEntity();
    	$em = $args->getEntityManager();
    	
    	if ($entity instanceof \My\UiBundle\Entity\Torrent) {
    		$search = new \My\UiBundle\Service\Search();
    		$search->updateTorrent($entity);
    	}    	
    }
}