<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = [
            "Data scientist",
            "Statisticien",
            "Analyste cyber-sécurité",
            "Médecin ORL",
            "Echographiste",
            "Mathématicien",
            "Ingénieur logiciel",
            "Analyste informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des Ressources Humaines",
            "Hygiéniste dentaire"
        ];
        foreach ($datas as $data) {
            $job = new Job();
            $job->setDesignation($data);
            $manager->persist($job);
        }
        $manager->flush();
    }
}
