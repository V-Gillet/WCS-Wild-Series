<?php

namespace App\DataFixtures;


use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class SeasonFixtures extends Fixture implements DependentFixtureInterface

{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($p = 0; $p < 5; $p++) {
            for ($i = 0; $i < 5; $i++) {

                $season = new Season();

                $season->setNumber($i);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                $season->setProgram($this->getReference('program_' . $p));

                $this->addReference('program_' . $p . 'season_' . $i, $season);

                $manager->persist($season);
            }
        }
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
