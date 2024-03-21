<?php

namespace Tests\AppBundle\Controller;

use App\Entity\User;

class UserControllerTest extends ControllerTestCase
{
    public function testListAction()
    {
        $this->loginAs('test', 'password');

        $crawler = $this->client()->request('GET', '/users');
        $this->assertStringContainsString('Créer un utilisateur', $crawler->filter('a.btn-primary')->text());
    }

    public function testCreateAction()
    {
        // Supprimer les éventuels utilisateurs de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery("DELETE FROM user WHERE email != 'test@example.com' AND email != 'author@example.com'");

        $this->loginAs('test', 'password');

        $crawler = $this->client()->request('GET', '/users/create');
        $this->assertStringContainsString('Nom d\'utilisateur', $crawler->filter('label')->text());

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form([
            'user[username]' => 'Utilisateur 1',
            'user[password][first]' => '$2y$13$Tx.3gKHSdcmxaTUuC.NO/uXsPIMcr7npAi.KLogalYwZ5o/x81LEi', // password hashed
            'user[password][second]' => '$2y$13$Tx.3gKHSdcmxaTUuC.NO/uXsPIMcr7npAi.KLogalYwZ5o/x81LEi', // password hashed
            'user[email]'    => 'utilisateur1@exemple.com',
        ]);

        $crawler = $this->client()->submit($form);

        $this->assertStringContainsString('Superbe ! L\'utilisateur a bien été ajouté.', $crawler->filter('div.alert-success')->text());
        $this->assertStringContainsString('Liste des utilisateurs', $crawler->html());
    }

    public function testEditAction()
    {
        // Supprimer les éventuelles tâches de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery("DELETE FROM user WHERE email != 'test@example.com' AND email != 'author@example.com'");

        $this->loginAs('test', 'password');

        $em = $this->client()->getContainer()->get('doctrine.orm.entity_manager');
        $user = new User();
        $user->setUsername('Utilisateur à modifier');
        $user->setPassword('$2y$13$Tx.3gKHSdcmxaTUuC.NO/uXsPIMcr7npAi.KLogalYwZ5o/x81LEi'); // password hashed
        $user->setEmail('utilisateuramodifier@example.com');
        $em->persist($user);
        $em->flush();

        $crawler = $this->client()->request('GET', '/users/'.$user->getId().'/edit');
        $this->assertStringContainsString('Nom d\'utilisateur', $crawler->filter('label')->text());
        $this->assertStringContainsString('Utilisateur à modifier', $crawler->filter('input#user_username')->attr('value'));

        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        $form = $buttonCrawlerNode->form([
            'user[username]' => 'Utilisateur modifié',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]'    => 'utilisateurmodifie@example.com',
        ]);

        $crawler = $this->client()->submit($form);

        $this->assertStringContainsString('Superbe ! L\'utilisateur a bien été modifié', $crawler->filter('div.alert-success')->text());
        $this->assertStringContainsString('Utilisateur modifié', $crawler->html());
    }
}