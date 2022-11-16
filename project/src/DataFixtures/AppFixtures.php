<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Factory::create('fr_FR');


        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word())
                        ->setPrice($faker->numberBetween(1, 100));

            $manager->persist($ingredient);
        }
        $manager->flush();
    }
}
