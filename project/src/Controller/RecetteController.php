<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette', name:'recette_index', methods: ['GET'])]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->findBy(['user' => $this->getUser()]);

        $recettes = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('/pages/recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    /**
     * Method to create recette
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/new', name: 'recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {

        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $recette = $form->getData();
            $recette->setUser($this->getUser());
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créer avec succès !'
            );
            return $this->redirectToRoute('recette_index');
        }
        return $this->renderForm('/pages/recette/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Method update Recette
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/update/{id}', name:'recette_update', methods:['GET', 'POST'])]
    public function update(EntityManagerInterface $manager, Recette $recette, Request $request): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec succès !'
            );

            return $this->redirectToRoute('recette_index');

        }

        return $this->render('pages/recette/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Method to delete recette
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/delete/{id}', name:'recette_delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager, Recette $recette): Response
    {
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            'La recette a bien été supprimé'
        );

        return $this->redirectToRoute('recette_index');
    }
}
