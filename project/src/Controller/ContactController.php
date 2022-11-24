<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods:['POST', 'GET'])]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $contact = new Contact();
        
        if($this->getUser()) {
            $contact->setFullname($this->getUser()->getFullName())  
            ->setEmail($this->getUser()->getEmail());          
        }
        
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $contact = $form->getData();
            $manager->persist($contact);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre message a bien été envoyé'
            );

            return $this->redirectToRoute('app_contact');
        }



        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
