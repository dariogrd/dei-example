<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\TaskType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = ['Dario', 'Jurica', 'Darko', 'Dejan', 'Marinko', 'Nikola'];
        $index = 0;
        foreach ($users as $name) {
            $index += 1;
            $user = $this->createUser($name);
            $manager->persist($user);
            if($index % 2 === 0) {
                $project = new Project('Projekt '.($index/2), $user);
                $manager->persist($project);
            }
        }

        $taskType = new TaskType();
        $taskType->setName('Bug');
        $taskType->setIcon('mdi-bug');
        $manager->persist($taskType);
        $taskType = new TaskType();
        $taskType->setName('Nova funkcionalnost');
        $taskType->setIcon('mdi-eye-plus');
        $manager->persist($taskType);
        $taskType = new TaskType();
        $taskType->setName('Zadatak');
        $taskType->setIcon('mdi-playlist-check');
        $manager->persist($taskType);

        $manager->flush();
    }

    private function createUser($name, $domain = 'foi.hr'): User
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail(strtolower($name).'@'.$domain);
        $user->setPassword($this->encoder->encodePassword($user, '123456'));
        if($name === 'Dario') $user->setRoles(['ROLE_ADMIN']);
        return $user;
    }
}
