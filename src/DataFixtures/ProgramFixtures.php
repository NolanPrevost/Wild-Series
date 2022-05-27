<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        "Star Wars Le retour du roi" => ["Le jedi roi du Gondor revient de la planète Dagoba pour sauver la Comté.", "Science-fiction"],
        "Billy the T-Rex" => ["Un monstre venu d'outre-tombe dévore des enfants à Mulhouse.", "Horreur"],
        "Arkham Paradise" => ["Une infirmière dévouée aide ses patients à retrouver le goût de la vie.", "Drame"],
        "Black Hole" => ["Un professeur en burn-out se plonge à corps perdu dans le snorkling.", "Comédie"],
        "Koh-Lanta" => ["L'équipe rouge tente à tout prix de trouver la Coconut Dorée malgré les trahisons répétée de Caroline.", "Action"],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::PROGRAMS as $title => $content){
            $program = new Program();
            $program->setTitle($title);
            $program->setSynopsis($content[0]);
            $program->setCategory($this->getReference('category_' . $content[1]));
            $manager->persist($program);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
