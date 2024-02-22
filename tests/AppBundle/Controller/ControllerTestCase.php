<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ControllerTestCase extends WebTestCase
{
    private $client;

    public function client()
    {
        if (!$this->client) {
            $this->client = static::createClient();
            $this->client->followRedirects();
        }

        return $this->client;
    }

    public function loginAs($username, $password)
    {
        $client = $this->client();

        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Se connecter');
        $form = $button->form(['_username' => $username, '_password' => $password]);

        return $client->submit($form);
    }
}
