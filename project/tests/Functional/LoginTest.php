<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    /**
     * Test login page with correct credentials. Should be redirected to home page
     *
     * @return void
     */
    public function testIfLoginIsSuccessfull(): void
    {
        $client = static::createClient();
        // $crawler = $client->request('GET', '/login');

        // get route by urlgenerator (on fait appel au container qui contient le service router)
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('security.login')); // On lui passe le name (dans la méthode du controller) de la route
        
        // Form
        $form = $crawler->filter("form[name=login]")->form([  // On récupère le formulaire par son name
            "_username" => "admin@admin.fr", // On insert les valeurs a tester dans les champs
            "_password" => "admin"
        ]); 
        
        $client->submit($form); // On soumet le formulaire
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // On dit au client de suivre la redirection prévu
        $client->followRedirect();

        // On demande au client de vérifier si la route est la meme que celle que l'on va passer
        $this->assertRouteSame('app_index');
    }

    /**
     * Test login with bad credentials. Should be redirected to login page with error message
     *
     * @return void
     */
    public function testIfLoginFailedWhenPasswordIsWrong(): void
    {
        $client = static::createClient();
        // $crawler = $client->request('GET', '/login');

        // get route by urlgenerator (on fait appel au container qui contient le service router)
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('security.login')); // On lui passe le name (dans la méthode du controller) de la route
        
        // Form
        $form = $crawler->filter("form[name=login]")->form([  // On récupère le formulaire par son name
            "_username" => "admin@admin.fr", // On insert les valeurs a tester dans les champs
            "_password" => "wrongPassword"
        ]); 
        
        $client->submit($form); // On soumet le formulaire
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // On dit au client de suivre la redirection prévu
        $client->followRedirect();

        // On demande au client de vérifier si la route est la meme que celle que l'on va passer
        $this->assertRouteSame('security.login');

        $this->assertSelectorTextContains("div.alert-danger", "Invalid credentials."); // On pointe sur la div avec le message pour verfiéer qu'on inscrit bien un message d'erreur
    }
        // $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
}
