<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        "Star Wars Le retour du roi" => ["Le jedi roi du Gondor revient de la planète Dagoba pour sauver la Comté.", "Science-fiction", 1],
        "Billy the T-Rex" => ["Un monstre venu d'outre-tombe dévore des enfants à Mulhouse.", "Horreur", 2],
        "Arkham Paradise" => ["Une infirmière dévouée aide ses patients à retrouver le goût de la vie.", "Drame", 3],
        "Black Hole" => ["Un professeur en burn-out se plonge à corps perdu dans le snorkling.", "Comédie", 4],
        "Koh-Lanta" => ["L'équipe rouge tente à tout prix de trouver la Coconut Dorée malgré les trahisons répétée de Caroline.", "Action", 5],
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (self::PROGRAMS as $title => $content) {
            $program = new Program();
            $program->setTitle($title);
            $program->setSynopsis($content[0]);
            $program->setCategory($this->getReference('category_' . $content[1]));
            $manager->persist($program);
            $this->addReference('program_' . $content[2], $program);
        }

        for($i = 0; $i < 10; $i++){
            $actor = new Actor();
            $actor->setName($faker->name());
            $actor->addProgram($this->getReference('program_' . rand(1, 5)));
            $actor->addProgram($this->getReference('program_' . rand(1, 5)));
            $actor->addProgram($this->getReference('program_' . rand(1, 5)));
            $manager->persist($actor);
        }

        for($i = 0; $i < 50; $i++) {
            $season = new Season();
            $season->setNumber($faker->numberBetween(1, 10));
            $season->setYear($faker->year());
            $season->setDescription($faker->paragraphs(3, true));
            $season->setProgram($this->getReference('program_' . rand(1, 5)));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }

        for($i = 0; $i < 200; $i++) {
            $episode = new Episode();
            $episode->setSeason($this->getReference('season_' . rand(0, 49)));
            $episode->setTitle($faker->words(3, true));
            $episode->setNumber(rand(1, 200));
            $episode->setSynopsis($faker->paragraph(5, true));
            $manager->persist($episode);
        }

        $manager->flush();
    }

    

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
