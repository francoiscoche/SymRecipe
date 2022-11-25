<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function PHPSTORM_META\map;

class ContactTest extends WebTestCase
{

    /**
     * Test of contact Page
     *
     * @return void
     */
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        // Récuperer le formulaire
        $submitButton = $crawler->selectButton('Submit'); // On récupere le button du formulaire
        $form = $submitButton->form(); // Ensuite, on récupere le formulaire associé a ce bouton
        
        $form["ccontact[fullname]"] = "Jean Dupont"; // On récupere les names
        $form["contact[email]"] = "test@test.com"; // On récupere les names
        $form["contact[subject]"] = "TEST"; // On récupere les names
        $form["contact[message]"] = "Test Test"; // On récupere les names
 
        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier l'envoi du mail
        // $this->assertEmailCount(1);

        $client->followRedirect();

        // Vérifier la presence du message de success
        $this->assertSelectorExists(
            'div.alert.alert-success.mt-4',
            'Votre message a bien été envoyé'
        );

    }
}
