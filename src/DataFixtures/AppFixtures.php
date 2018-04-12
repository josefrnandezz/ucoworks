<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadStudents($manager);
    }

    private function loadStudents(ObjectManager $manager)
    {
        $users = [
            [ 'admin', ['ROLE_ADMIN'] ],
            [ 'josefrnandezz', ['ROLE_USER'] ],
        ];

        foreach ($users as $user) {
            $entity = new Student();
            $entity->setUsername($user[0]);
            $entity->setEmail("{$user[0]}@localhost.localdomain");
            $entity->setRoles($user[1]);
            $password = $this->encoder->encodePassword($entity, 'secret');
            $entity->setPassword($password);

            $manager->persist($entity);
            $this->addReference($user[0], $entity);
        }

        $manager->flush();
    }
}
