<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use My\UiBundle\Repository\UploaderRepository;

use My\UiBundle\Entity\Uploader;

class UploaderManager
{
    private $em;
    /** @var UploaderRepository */
	private $repo;

    /**
     * Construct
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository('MyUiBundle:Uploader');
    }

    /**
     * Create
     * @param $name
     * @return Uploader
     */
    public function create($name)
    {
        $uploader = new Uploader();
        $uploader->setName($name);
        $uploader->setCreatedAt(new \DateTime());
        $this->em->persist($uploader);
        $this->em->flush();
        return $uploader;
    }

    /**
     * Obtain uploader
     * @param $name
     * @return Uploader
     */
    public function obtainUploader($name)
    {
        $uploader = $this->findByName($name);
        if (!$uploader) {
            $uploader = $this->create($name);
        }
        return $uploader;
    }

	////////////////////////////////////////////////////////
    // REPO
	////////////////////////////////////////////////////////

    /**
     * Find
     * @return Uploader
     */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /**
     * Find All
     * @return Uploader[]
     */
    public function findAll()
    {
        return $this->repo->findAll();
    }

    /**
     * Find uploader by name
     * @param $name
     * @return Uploader
     */
    public function findByName($name)
    {
        return $this->repo->findOneByName($name);
    }
}
