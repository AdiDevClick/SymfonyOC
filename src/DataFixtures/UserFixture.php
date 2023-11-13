<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $user = new Users();
            $user->setFirstname($faker->firstName);
            $user->setName($faker->name);
            $user->setAge($faker->numberBetween(18, 65));


            $manager->persist($user);
        }
        $manager->flush();
    }
}
