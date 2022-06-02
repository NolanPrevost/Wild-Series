<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
// use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 0; $i < 50; $i++) {
            $season = new Season();
            $season->setNumber($faker->numberBetween(1, 10));
            $season->setYear($faker->year());
            $season->setDescription($faker->paragraphs(3, true));

            $season->setProgram($this->getReference('program_' . $faker->numberBetween(0, 5)));

            $manager->persist($season);
        }

        $manager->flush();
    }
}
