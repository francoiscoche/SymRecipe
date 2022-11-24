<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods:['POST', 'GET'])]
    public function index(EntityManagerInterface $manager, Request $request, MailerInterface $mailer): Response
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


            // Email
            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('admin@symrecipe.com')
            ->subject($contact->getSubject())
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'contact' => $contact
            ]);
            // ->text($contact->getMessage())
    
            $mailer->send($email);

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
