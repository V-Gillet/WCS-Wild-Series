<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const CATEGORIES = [
        [
            'Title' => 'Breaking bad',
            'Synopsis' => 'Un prof de maths fait de la meth',
            'Category' => 'category_Aventure',
        ],
        [
            'Title' => 'Vikings',
            'Synopsis' => 'Des vikings qui font la bagarre',
            'Category' => 'category_Action',
        ],
        [
            'Title' => 'Scrubs',
            'Synopsis' => 'La meilleure des séries du monde',
            'Category' => 'category_Comedie',
        ],
        [
            'Title' => 'Gravity falls',
            'Synopsis' => 'Des mystères à résoudre avec Bill Cypher',
            'Category' => 'category_Animation',
        ],
        [
            'Title' => 'The mandalorian',
            'Synopsis' => 'Baby Yoda se fait la malle',
            'Category' => 'category_Sciences-fiction',
        ],

    ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::CATEGORIES as $p => $category) {
            $program = new Program();

            $program->setTitle($category['Title']);
            $program->setSynopsis($category['Synopsis']);
            $program->setCategory($this->getReference($category['Category']));

            $this->addReference('program_' . $p, $program);

            $manager->persist($program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {

        return [CategoryFixtures::class,];
    }
}
