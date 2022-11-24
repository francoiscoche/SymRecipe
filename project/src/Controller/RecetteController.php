<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Form\MarkType;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\MarkRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    /**
     * The controller display all recipes
     *
     * @param RecetteRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name:'recette_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
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

    #[Route('/recette/public', name:'recette.index.public', methods: ['GET'])]
    public function indexPublic(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $recette = $paginator->paginate(
            $repository->findPublicRecipe(null),
            $request->query->getInt('page', 1), /*page number*/
            10
        );


        return $this->render('pages/recette/index_public.html.twig', [
            'recettes' => $recette
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
    #[IsGranted('ROLE_USER')]
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
    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    public function update(EntityManagerInterface $manager, Recette $recette, Request $request): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        // dd($form->getData());
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

    /**
     * Display recipe with ID if this one is public
     *
     * @param Recette $recette
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and (recette.getIsPublic() === true || user === recette.getUser())")]
    #[Route('/recette/{id}', name:'recette.show', methods: ['GET', 'POST'])]
    public function show(MarkRepository $repository, Recette $recette, Request $request, EntityManagerInterface $manager) : Response
    {

        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);

        // dd($form->getData());

        if($form->isSubmitted() && $form->isValid())
        {
            $mark->setUser($this->getUser())
                ->setRecette($recette);


            $existingMark = $repository->findOneBy([
                'user' => $this->getUser(),
                'recette' => $recette
            ]);

            if(!$existingMark) {
                $manager->persist($mark);

                $this->addFlash(
                    'success',
                    'Votre note a bien été prise en compte'
                );

            } else {
                $this->addFlash(
                    'warning',
                    'Votre note a été mise a jour'
                );

                $existingMark->setMark($form->getData()->getMark());
                $manager->persist($existingMark);
            }

            $manager->flush();
            return $this->redirectToRoute('recette.show', ['id' => $recette->getId()]);
        }

        return $this->render('pages/recette/show.html.twig', [
            'recette' => $recette,
            'form' => $form->createView()
            // 'mark' => $repository->getAverageNotationByRecipe($id)
        ]);
    }

}
