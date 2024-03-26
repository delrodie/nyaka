<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_participation_index')]
    public function index(): Response
    {
        return $this->render('frontend/participation_section.html.twig',[
            'vicariats' => $this->allRepositories->getAllVicariat("ASC"),
        ]);
    }

    #[Route('/identite', name: 'app_participation_identite')]
    public function identite(): Response
    {
        return $this->render('frontend/participation_identite.html.twig');
    }

    #[Route('/paiement/base', name: 'app_participation_paiement')]
    public function paiement(): Response
    {
        return $this->render('frontend/participation_paiement.html.twig');
    }

}