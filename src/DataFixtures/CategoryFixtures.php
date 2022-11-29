<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Sciences-fiction',
        'Horreur',
        'Comedie',
    ];

    public function load(ObjectManager $manager)
    {

        foreach (self::CATEGORIES as $categoryName) {

            $category = new Category();

            $category->setName($categoryName);

            $manager->persist($category);

            $this->addReference('category_' . $categoryName, $category);
        }

        $manager->flush();
    }
}
