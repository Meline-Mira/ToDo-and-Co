<?php

namespace Tests\AppBundle\Controller;

class SecurityControllerTest extends ControllerTestCase
{
    public function testLoginSuccess()
    {
        $crawler = $this->loginAs('test', 'password');

        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('h1')->text());
    }

    public function testLoginFail()
    {
        $crawler = $this->loginAs('test', 'badpassword');

        $this->assertContains('Invalid credentials.', $crawler->filter('div.alert-danger')->text());
    }

    public function testLogout()
    {
        $crawler = $this->loginAs('test', 'password');

        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('h1')->text());

        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $this->client()->click($link);

        $this->assertContains('Nom d\'utilisateur', $crawler->filter('label')->text());
    }
}