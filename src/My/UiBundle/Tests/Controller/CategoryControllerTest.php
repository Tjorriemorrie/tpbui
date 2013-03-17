<?php

namespace My\UiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

	    $id = 1;

        $crawler = $client->request('GET', '/category/' . $id);

	    $this->assertTrue($client->getResponse()->isSuccessful());
	    $this->assertCount(1, $crawler->filter('.nav li.active'));

	    $this->assertEquals($id, $crawler->filter('#category')->attr('data-category'));
    }
}
