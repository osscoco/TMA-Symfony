<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Tweet;
use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private $users;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();

        $this->addUsers($manager);
        $this->addTweets($manager);

        $manager->flush();
    }

    private function addUsers(EntityManager $em)
    {
        //Admin
        $user = new User();
        $firstname = "root";
        $lastname = "root";
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($firstname.'.'.$lastname.'@iia-formation.fr');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            "rootroot"
        );  
        $user->setPassword($hashedPassword);
        $user->setDateCreation(new \DateTime('@'.strtotime('now')));
        $user->setDateModification(new \DateTime('@'.strtotime('now')));
        $em->persist($user);

        $this->users[] = $user;

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $firstname = $this->faker->firstName;
            $lastname = $this->faker->lastName;
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($firstname.'.'.$lastname.'@iia-formation.fr');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                "Not24get"
            );  
            $user->setPassword($hashedPassword);
            $user->setDateCreation(new \DateTime('@'.strtotime('now')));
            $user->setDateModification(new \DateTime('@'.strtotime('now')));
            $em->persist($user);

            $this->users[] = $user;
        }
    }

    public function addTweets(EntityManager $em)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $tweet = new Tweet();
            $tweet->setLabel($this->faker->firstName . " " . $this->faker->lastName . " vient de poster ce tweet !");
            $tweet->setLikes($faker->numberBetween($min = 0, $max = 300));
            $tweet->setDateCreation(new \DateTime('@'.strtotime('now')));
            $tweet->setDateModification(new \DateTime('@'.strtotime('now')));
            $tweet->setUser($this->users[rand(0, 9)]);
            $em->persist($tweet);
        }
    }
}
