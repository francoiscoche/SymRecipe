<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserController extends AbstractController
{
    /**
     * This controller allow us to update user profil
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/user/edit/{id}', name: 'user.edit', methods: ['POST', 'GET'])]
    public function index(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {

        // Check if the user is connected
        if(!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        // Check is the user connected is the same that the one trying to update his profile
        if($this->getUser() !== $user) {
            return $this->redirectToRoute('app_index');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword()))
            {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Le profil a bien été modifié'
                );
    
                return $this->redirectToRoute('recette_index');
            } else {

                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }



        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('utilisateur/edit-password/{id}', name: 'user.edit.password', methods: ['POST', 'GET'])]
    public function editPassword(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response 
    {

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($hasher->isPasswordValid($user, $form->getData()['plainPassword']))
            {

                // Utilsation de hashpassword directement dans le controller et non pas grace a l'entity listener, car on a vu que l'update dans l'entity listerner ne fonctionne pas
                // Il semble que ce serait un probleme connu de symfony
                // On encode donc directement le mot de passe dans le controller avec la methode hashPassword 
                $user->setPassword($hasher->hashPassword($user, $form->getData()['newPassword']));

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le profil a bien été modifié'
                );
    
                return $this->redirectToRoute('recette_index');

            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
