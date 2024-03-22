<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        return $this->render('frontend/participation_section.html.twig',[
            'vicariats' => $this->allRepositories->getAllVicariat("ASC"),
        ]);
    }
}