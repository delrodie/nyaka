<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/section', name: 'app_participation_section', methods: ['POST'])]
    public function section(Request $request)
    {
//        dd($request);
        $base = [
            'vicariat_id' => (int) $request->get('selectVicariat'),
            'doyenne_id' => (int) $request->get('selectDoyenne'),
            'section_id' => (int) $request->get('selectSection')
        ];

        $request->getSession()->set('base', $base);

        return $this->render('frontend/participation_identite.html.twig');
    }
}