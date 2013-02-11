<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use My\UiBundle\Repository\CategoryRepository;

class CategoryManager
{
    private $em;
    /** @var CategoryRepository */
	private $repo;

    /**
     * Construct
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository('MyUiBundle:Category');
    }

	////////////////////////////////////////////////////////
    // REPO
	////////////////////////////////////////////////////////
}
