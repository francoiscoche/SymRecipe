<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $button = $crawler->filter(".btn.btn-primary.btn-lg");
        $this->assertEquals(1, count($button));

        $recette = $crawler->filter('.card.text-white.bg-primary.mb-3.mx-3');
        $this->assertEquals(3, count($recette));

        // $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello, world!');
    }
}
