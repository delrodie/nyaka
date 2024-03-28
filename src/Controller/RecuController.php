<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recu')]
class RecuController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/{matricule}', name: 'app_recu_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        return $this->render('frontend/recu_search.html.twig',[
            'aspirant' => $this->allRepositories->getOneAspirant(null, $matricule),
        ]);
    }
}