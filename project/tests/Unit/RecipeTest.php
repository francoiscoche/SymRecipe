<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{

    public function getEntity(): Recette
    {
        return (new Recette())
                            ->setName("Name #1")
                            ->setProcess('Description #1')
                            ->setFavorites(true)
                            ->setCreatedAt(new \DateTimeImmutable())
                            ->setUpdatedAt(new \DateTimeImmutable());
    }


    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();

        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(0, $errors); // On demande a ce que le nombre d'erreur que l'on attend soit de zero
    }

    /**
     * This testing an empty name when insert a new recipe
     *
     * @return void
     */
    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setName('');

        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(2, $errors); // Ici on s'attends a deux erreurs car dans notre entity Recette, pour le champs Name, on a deux asseerts (notBlank et length)
    }

    /**
     * This testing the getAverage() methods in Recette entity
     *
     * @return void
     */
    public function testGetAverage()
    {
        $recipe = $this->getEntity();
        $container = static::getContainer();

        // On lui demande d'aller chercher le service, entityManager, pour récupérer des elements depuis la database
        // Trouve moi le user, avec l'ID 1
        $user = $container->get('doctrine.orm.entity_manager')->find(User::class, 1);

        for ($i=0; $i < 5 ; $i++) { 

            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecette($recipe);
        }

        $recipe->addMark($mark);

        $this->assertTrue(2.0 === $recipe->getAverage());
    }
}
