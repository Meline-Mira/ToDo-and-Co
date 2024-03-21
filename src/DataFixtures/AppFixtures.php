<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher, private string $env)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        if ($this->env === 'test') {
            $user = new User();
            $user->setUsername('test');
            $user->setEmail('test@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);

            $user = new User();
            $user->setUsername('author');
            $user->setEmail('author@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        } else {
            $user = new User();
            $user->setUsername('admin');
            $user->setEmail('admin@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);

            $users = [];

            for ($letter = 'a'; $letter <= 'i'; $letter++) {
                $user = new User();
                $user->setUsername('utilisateur ' . $letter);
                $user->setEmail('utilisateur'.$letter.'@example.com');
                $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
                $user->setRoles(['ROLE_USER']);

                $users[] = $user;

                $manager->persist($user);
            }

            for ($i = 1; $i <= 5; $i++) {
                $task = new Task();
                $task->setTitle('Tâche ' . $i);
                $task->setContent('Description de la tâche ' . $i);

                $manager->persist($task);
            }

            for ($i = 6; $i <= 10; $i++) {
                $task = new Task();
                $task->setTitle('Tâche ' . $i);
                $task->setContent('Description de la tâche ' . $i);
                $task->setAuthor($users[mt_rand(0, count($users) - 1)]);

                $manager->persist($task);
            }
        }

        $manager->flush();
    }
}