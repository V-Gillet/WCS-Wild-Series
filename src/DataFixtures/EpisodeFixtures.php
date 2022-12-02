<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface

{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($p = 0; $p < 5; $p++) {
            for ($i = 0; $i < 5; $i++) {
                for ($j = 0; $j < 10; $j++) {

                    $episode = new Episode();

                    $episode->setTitle($faker->words(1, 3));
                    $episode->setNumber($j);
                    $episode->setSynopsis($faker->paragraphs(2, true));
                    $episode->setSeason($this->getReference('program_' . $p . 'season_' . $i));

                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
