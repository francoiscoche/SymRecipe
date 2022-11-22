<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name:'app_index', methods:['GET'])]
    public function index(RecetteRepository $recette):Response
    {
        return $this->render('pages/home.html.twig', [
            'recettes' => $recette->findPublicRecipe(3)
        ]);
    }
}