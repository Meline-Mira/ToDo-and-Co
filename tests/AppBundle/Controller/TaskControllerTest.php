<?php

namespace Tests\AppBundle\Controller;

use App\Entity\Task;
use DateTimeImmutable;

class TaskControllerTest extends ControllerTestCase
{
    public function testListAction()
    {
        $this->loginAs('test', 'password');

        $crawler = $this->client()->request('GET', '/tasks');
        $this->assertStringContainsString('Créer une tâche', $crawler->filter('a.btn-info')->text());
    }

    public function testCreateAction()
    {
        // Supprimer les éventuelles tâches de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery('DELETE FROM task');

        $this->loginAs('test', 'password');

        $crawler = $this->client()->request('GET', '/tasks/create');
        $this->assertStringContainsString('Title', $crawler->filter('label')->text());

        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $form = $buttonCrawlerNode->form([
            'task[title]'    => 'Tâche à faire',
            'task[content]' => 'Description',
        ]);

        $crawler = $this->client()->submit($form);

        $this->assertStringContainsString('Superbe ! La tâche a été bien été ajoutée.', $crawler->filter('div.alert-success')->text());
        $this->assertStringContainsString('Tâche à faire', $crawler->html());
    }

    public function testEditAction()
    {
        // Supprimer les éventuelles tâches de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery('DELETE FROM task');

        $this->loginAs('test', 'password');

        $em = $this->client()->getContainer()->get('doctrine.orm.entity_manager');
        $task = new Task();
        $task->setTitle('Tâche à modifier');
        $task->setContent('Description à modifier');
        $task->setCreatedAt(new DateTimeImmutable('22-02-2024'));
        $em->persist($task);
        $em->flush();

        $crawler = $this->client()->request('GET', '/tasks/'.$task->getId().'/edit');
        $this->assertStringContainsString('Title', $crawler->filter('label')->text());
        $this->assertStringContainsString('Description à modifier', $crawler->filter('textarea')->text());

        $buttonCrawlerNode = $crawler->selectButton('Modifier');

        $form = $buttonCrawlerNode->form([
            'task[title]'    => 'Tâche modifiée',
            'task[content]' => 'Description modifiée',
        ]);

        $crawler = $this->client()->submit($form);

        $this->assertStringContainsString('Superbe ! La tâche a bien été modifiée.', $crawler->filter('div.alert-success')->text());
        $this->assertStringContainsString('Tâche modifiée', $crawler->html());
    }

    public function testToggleTaskAction()
    {
        // Supprimer les éventuelles tâches de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery('DELETE FROM task');

        $this->loginAs('test', 'password');

        $em = $this->client()->getContainer()->get('doctrine.orm.entity_manager');
        $task = new Task();
        $task->setTitle('Tâche à faire');
        $task->setContent('Description de la tâche');
        $task->setCreatedAt(new DateTimeImmutable('22-02-2024'));
        $em->persist($task);
        $em->flush();

        $crawler = $this->client()->request('GET', '/tasks/'.$task->getId().'/toggle');
        $this->assertStringContainsString('Marquer non terminée', $crawler->filter('button')->text());

        $crawler = $this->client()->request('GET', '/tasks/'.$task->getId().'/toggle');
        $this->assertStringContainsString('Marquer comme faite', $crawler->filter('button')->text());
    }

    public function testDeleteTaskAction()
    {
        // Supprimer les éventuelles tâches de la base de données
        $this->client()->getContainer()->get('database_connection')->executeQuery('DELETE FROM task');

        $this->loginAs('test', 'password');

        $em = $this->client()->getContainer()->get('doctrine.orm.entity_manager');
        $task = new Task();
        $task->setTitle('Tâche à faire');
        $task->setContent('Description de la tâche');
        $task->setCreatedAt(new DateTimeImmutable('22-02-2024'));
        $em->persist($task);
        $em->flush();

        $crawler = $this->client()->request('GET', '/tasks/'.$task->getId().'/delete');
        $this->assertStringContainsString('Superbe ! La tâche a bien été supprimée.', $crawler->filter('div.alert-success')->text());
    }
}