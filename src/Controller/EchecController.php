<?php

namespace App\Controller;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/echec')]
class EchecController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/{matricule}', name: 'app_echec_wave', methods: ['GET'])]
    public function wave($matricule)
    {
//        😔😢😞😩😫😭😥☹️😣
        return $this->render('frontend/wave_echec_paiement.html.twig',[
            'aspirant' => $this->allRepositories->getOneAspirant(null, $matricule)
        ]);
    }
}