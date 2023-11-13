<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setSocials('Facebook');
        $profile->setUrl('https://www.facebook.com/adrien.quijo');

        $profile2 = new Profile();
        $profile2->setSocials('Twitter');
        $profile2->setUrl('https://twitter.com/adi81396928');

        $profile1 = new Profile();
        $profile1->setSocials('GitHub');
        $profile1->setUrl('https://github.com/AdiDevClick/');

        $profile3 = new Profile();
        $profile3->setSocials('LinkedIn');
        $profile3->setUrl('https://www.linkedin.com/in/adrien-quijo/');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
    }
}
