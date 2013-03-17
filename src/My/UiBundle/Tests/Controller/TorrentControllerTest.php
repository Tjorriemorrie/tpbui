<?php

namespace My\UiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TorrentControllerTest extends WebTestCase
{
    public function testDownload()
    {
        $client = static::createClient();

	    $torrentId = 3463119;

        $crawler = $client->request('GET', '/torrent/download/' . $torrentId);

	    $this->assertTrue($client->getResponse()->isSuccessful());
	    $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

	    $response = json_decode($client->getResponse()->getContent());
	    //die(var_dump($response));
	    $this->assertEquals('OK', $response);
    }

    public function testUnwanted()
    {
        $client = static::createClient();

	    $torrentId = 3463119;

        $crawler = $client->request('GET', '/torrent/unwanted/' . $torrentId);

	    $this->assertTrue($client->getResponse()->isSuccessful());
	    $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));

	    $response = json_decode($client->getResponse()->getContent());
	    //die(var_dump($response));
	    $this->assertEquals('OK', $response);
    }
}
