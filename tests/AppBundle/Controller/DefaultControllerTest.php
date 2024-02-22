<?php

namespace Tests\AppBundle\Controller;

class DefaultControllerTest extends ControllerTestCase
{
    public function testIndexRedirection()
    {
        $client = $this->client();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Nom d\'utilisateur :', $crawler->filter('label')->text());
    }
}
