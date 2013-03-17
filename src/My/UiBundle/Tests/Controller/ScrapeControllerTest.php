<?php

namespace My\UiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScrapeControllerTest extends WebTestCase
{
	/**
	 * Test scrape::index
	 */
	public function testIndex()
    {
        $client = static::createClient();

	    $categoryId = 1;
	    $pageNumber = 1;

        $crawler = $client->request('GET', '/scrape/' . $categoryId . '/' . $pageNumber);

	    $this->assertTrue($client->getResponse()->isSuccessful());
	    $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

	    $response = json_decode($client->getResponse()->getContent());
	    //die(var_dump($response));
	    $this->assertEquals('html', key($response));
    }
}
