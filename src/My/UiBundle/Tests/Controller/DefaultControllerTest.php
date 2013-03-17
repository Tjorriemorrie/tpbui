<?php

namespace My\UiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

	    $this->assertTrue($client->getResponse()->isSuccessful());

	    $this->assertEquals('TPB UI', $crawler->filter('.brand')->text());
    }
}
