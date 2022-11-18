<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Factory::create('fr_FR');
        $ingredients = [];

        // Ingrédients
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word())
                        ->setPrice($faker->numberBetween(1, 100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }


        // Recettes
        for ($j=0; $j < 25; $j++) {
            $recipe = new Recette();
            $recipe->setName($faker->word())
                    ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1400): null)
                    ->setNumberPersonne(mt_rand(0,1) == 1 ? mt_rand(1,50): null)
                    ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1,5): null)
                    ->setProcess($faker->text(300))
                    ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000): null)
                    ->setFavorites(mt_rand(0,1) == 1 ? true: false);

            for ($k=0; $k < mt_rand(5,15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $manager->persist($recipe);
        }


        $manager->flush();
    }
}
