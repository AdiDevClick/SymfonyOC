<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $datas = [
            "Fabriquer des bijoux",
            "Photographie",
            "Blogging",
            "Apprendre une langue",
            "Dessin",
            "Coloriage",
            "Peinture",
            "Se lancer dans le tissage de tapis",
            "Créer des vêtements ou des cosplay",
            "Yoga",
            "Cuisine",
            "Pâtisserie",
            "Décorer des galets",
            "Travailler le métal",
            "Faire des puzzles avec de plus en plus de pièces",
            "Créer des miniatures (maisons, voitures, train, bateaux...)",
            "Améliorer son espace de vie",
            "Apprendre à jongler",
            "Faire partie d'un club de lecture",
            "Apprendre la programmation informatique",
            "En apprendre plus sur le survivalisme",
            "Créer une chaine Youtube",
            "Jouer aux fléchettes",
            "Apprendre à chanter"
        ];
        foreach ($datas as $data) {
            $hobby = new Hobby();
            $hobby->setDesignation($data);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
