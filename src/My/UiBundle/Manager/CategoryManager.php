<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use My\UiBundle\Repository\CategoryRepository;

use My\UiBundle\Entity\Category;

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

    /**
     * Find
     * @return Category
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * Find All
     * @return Category[]
     */
    public function findAll()
    {
        return $this->repo->findAll();
    }
}
