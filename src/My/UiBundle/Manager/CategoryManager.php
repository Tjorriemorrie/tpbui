<?php

namespace My\UiBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;
use My\UiBundle\Repository\CategoryRepository;
use My\UiBundle\Entity\Category;
use My\UiBundle\Entity\Torrent;

class CategoryManager
{
    protected $em;
    /** @var CategoryRepository */
	protected $repo;
    protected $validator;

    /**
     * Construct
     */
    public function __construct(EntityManager $em, Validator $validator)
    {
        $this->em = $em;
        $this->repo = $em->getRepository('MyUiBundle:Category');
        $this->validator = $validator;
    }

    /**
     * Validate
     */
    protected function validate(Category $category)
    {
        $violations = $this->validator->validate($category);
        if ($violations->count()) {
            throw new \Exception((string)$violations);
        }
    }

    /**
     * Load categories
     * @return Category[]
     */
    public function loadCategories()
    {
        $data = array(
            101 => 'Music', 102 => 'Audiobooks', 103 => 'Sound clips', 104 => 'FLAC', 199 => 'Other',
            201 => 'Movies', 202 => 'Movies DVDR', 203 => 'Music videos', 204 => 'Movie clips', 205 => 'TV shows', 206 => 'Handheld', 207 => 'HD Movies', 208 => 'HD TV Shows', 209 => '3D', 299 => 'Other',
            301 => 'Windows', 302 => 'Mac', 303 => 'Unix', 304 => 'Handheld', 305 => 'iOS (iPad/iPhone)', 306 => 'Android', 399 => 'Other OS',
            401 => 'PC', 402 => 'Mac', 403 => 'PSx', 404 => 'Xbox360', 405 => 'Wii', 406 => 'Handheld', 407 => 'iOS (iPad/iPhone)', 408 => 'Android', 499 => 'Other',
            501 => 'Movies', 502 => 'Movies DVDR', 503 => 'Pictures', 504 => 'Games', 505 => 'HD Movies', 506 => 'Movie clips', 599 => 'Other',
            601 => 'eBooks', 602 => 'Comics', 603 => 'Pictures', 604 => 'Covers', 605 => 'Physibles', 699 => 'Other'
        );

        foreach ($data as $code => $name) {
            $category = $this->findByCode($code);
            if (!$category) {
                $category = $this->create($code, $name);
            }
        }

        $this->em->flush();
    }

    /**
     * Create category
     * @param $code
     * @param $name
     * @return Category
     */
    public function create($code, $name)
    {
        $category = new Category();
        $this->em->persist($category);

        $category->setCode($code);
        $category->setSection(floor($code / 100) * 100);
        $category->setName($name);
        $category->setPages(1);
        $category->setCreatedAt(new \DateTime());

        return $category;
    }

    /**
     * Check pages
     * @param Category $category
     */
    public function checkPages(Torrent $torrent)
    {
        $category = $torrent->getCategory();
        if ($torrent->getPage() >= $category->getPages()) {
            $category->setPages($category->getPages() + 1);
            $this->em->flush();
        }
    }

    /**
     * Set viewed
     * @param Category $category
     * @return \DateTime
     */
    public function setViewed(Category $category)
    {
        $date = new \DateTime();
        $category->setLastViewedAt($date);
        $this->validate($category);

        $torrentsOldDownloaded = $category->getTorrentsOldDownloaded();
        //die(var_dump(count($torrentsOldDownloaded)));
        foreach ($torrentsOldDownloaded as $torrent) {
            $torrent->setStatus(Torrent::STATUS_UNWANTED);
            $torrent->setUpdatedAt(new \DateTime());
        }

        $this->em->flush();
        return $date;
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

    /**
     * find by code
     * @param $code
     * @return Category
     */
    public function findByCode($code)
    {
        return $this->repo->findOneByCode($code);
    }

    /**
     * Find by section
     * @param $section
     * @return Category[]
     */
    public function findBySection($section)
    {
        return $this->repo->findBySection($section);
    }

    /**
     * Find last viewed
     * @return Category[]
     */
    public function findLastViewed()
    {
        return $this->repo->findLastViewed();
    }
}
