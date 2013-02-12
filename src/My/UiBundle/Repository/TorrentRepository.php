<?php

namespace My\UiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use My\UiBundle\Entity\Category;

/**
 * TorrentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TorrentRepository extends EntityRepository
{
	/**
	 * Find by category and page
     * @return Torrent[]
	 */
	public function findByCategoryAndPage(Category $category, $page)
	{
        $query = $this->getEntityManager()->createQuery("
            SELECT t
            FROM MyUiBundle:Torrent t
            WHERE t.category = :categoryId
            AND t.page = :page
            ORDER BY t.popularity DESC
        ")->setParameters(array(
            'categoryId' => $category->getId(),
            'page' => $page
        ));
		return $query->getResult();
	}


	/**
	 * Increments the page
	 */
	public function incrementPage(Category $category, $page)
	{
        $query = $this->getEntityManager()->createQuery("
            UPDATE MyUiBundle:Torrent t
            SET t.page = :pageSet
            WHERE t.page = :pageCurrent
            AND t.category = :category
        ")->setParameters(array(
            'category' => $category->getId(),
            'pageSet' => $page + 1,
            'pageCurrent' => $page
        ));
        $query->execute();
	}


	public function findUpdateLast($category, $page)
	{
//		die(var_dump($category));
//		die(var_dump($page));
		$qb = $this->getEntityManager()->createQueryBuilder();
		try {
			return $qb->select($qb->expr()->min('i.updatedAt'))
				->from('MyUiBundle:Item', 'i')
				->where('i.page = ?2')->setParameter(2, $page)
				->andWhere('i.category = ?3')->setParameter(3, $category)
				->orderBy('i.updatedAt', 'ASC')
				->addOrderBy('i.createdAt', 'ASC')
				->getQuery()
				->getSingleScalarResult();
		} catch (\Exception $e) {}
	}


	/**
	 * Find Category's page's average popularity
	 */
	public function findCategoryPagePopularity($category, $page)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		try {
			return $qb->select($qb->expr()->avg('i.popularity'))
				->from('MyUiBundle:Item', 'i')
				->where('i.page = ?2')->setParameter(2, $page)
				->andWhere('i.category = ?3')->setParameter(3, $category)
				->getQuery()
				->getSingleScalarResult();
		} catch (\Exception $e) {}
	}
}
