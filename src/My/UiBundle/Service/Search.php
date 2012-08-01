<?php

namespace My\UiBundle\Service;

/**
 * Lucene Search
 */
class Search
{
	private function getIndex()
	{
		try {
			$index = \Zend_Search_Lucene::open(DIR_SEARCH);
		} catch (\Exception $e) {
			$index = \Zend_Search_Lucene::create(DIR_SEARCH);
		}

		return $index;
	}

	
	/**
	 * Index size
	 */
	public function getNumDocs()
	{
		$index = $this->getIndex();
		
		return $index->numDocs();
	}

	
	/**
	 * Optimize index
	 */
	public function optimize()
	{
		return $this->getIndex()->optimize();
	}
	
	
	/**
	 * Search articles
	 */
	public function search($query, $category = null, $uploaders = null)
	{
		$index = $this->getIndex();
		
		$query = $this->getTitle($query);
		$query = $this->removeTail($query, $uploaders);
		
		$search = 'title:"' . $query . '"';
		if (!is_null($category)) {
			$search .= ' AND category:"' . $category . '"';
		}
		
		$search = $query;
		
		$hits = $index->find($search);
		return array('hits'=>$hits, 'q'=>$search);
	}

    	
	/**
	 * Remove torrent
	 */
	public function removeTorrent($torrent)
	{
		$index = $this->getIndex();
		
		$hits = $index->find('unique:' . $torrent->getId());
		if (count($hits)) foreach ($hits as $hit) {
			$index->delete($hit->id);
		}
		
		$index->commit();
	}


	/**
	 * Add Torrent
	 */
	public function addTorrent($torrent)
	{
		$index = $this->getIndex();
		
		$doc = new \Zend_Search_Lucene_Document();
		$doc->addField(\Zend_Search_Lucene_Field::Keyword('unique', $torrent->getId()));
		$doc->addField(\Zend_Search_Lucene_Field::Text('title', $this->getTitle($torrent->getTitleOriginal())));
		$doc->addField(\Zend_Search_Lucene_Field::Text('category', $torrent->getCategory()->getName()));
		
		$index->addDocument($doc);
		
		$index->commit();
	}
	

	/**
	 * Update torrent
	 */
	public function updateTorrent($torrent)
	{
		$this->removeTorrent($torrent);
		$this->addTorrent($torrent);
	}
	
	
	/**
	 * Filter out anything but letters and numbers
	 */
	private function getTitle($str)
	{
		return preg_replace('/[^a-zA-Z0-9 ]/', ' ', $str);
	}
	
	
	/**
	 * Remove Tail
	 */
	private function removeTail($str, $uploaders = null)
	{
		$split = explode(' ', $str);
		
		foreach ($split as $key => $item) {
			if (empty($item)) unset($split[$key]);
			elseif (!is_null($uploaders) && in_array($item, $uploaders)) unset($split[$key]);
		}
		if (count($split) <= 3) $limit = 2;
		elseif (count($split) <= 5) $limit = 3;
		elseif (count($split) <= 8) $limit = 4;
		elseif (count($split) <= 10) $limit = 5;
		elseif (count($split) <= 12) $limit = 6;
		else $limit = 7;

		//$partsWanted = count($split) - 4;
		while (count($split) > $limit) {// && count($split) > $partsWanted) {
			array_pop($split);
		}
			
		return implode(' ', $split);
	}
}



