<?php

namespace App\Controller\Api;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/participation')]
class ApiParticipationController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_api_participation_section', methods: ['POST'])]
    public function section(Request $request)
    {
        $getContent = $request->getContent();
        $requestContent = json_decode($getContent, true);

        // Accéder aux données du tableau associatif
        $base = [
            'vicariat' => (int) $requestContent['selectVicariat'],
            'doyenne' => (int) $requestContent['selectDoyenne'],
            'section' => (int) $requestContent['selectSection']
        ];

        // Enregistrement en session des données
        $request->getSession()->set('base', $base);

        $message = [
            'statut' => true,
            'base' => $base
        ];

        return new JsonResponse($message, Response::HTTP_OK);
    }

    #[Route('/identite', name: 'app_api_participation_identite', methods: ['POST'])]
    public function identite(Request $request)
    {
        $getContent = json_decode($request->getContent(), true);

        $identite = [
            'nom' => (string) $getContent['nom'],
            'prenom' => (string) $getContent['prenom'],
            'contact' => $getContent['contact'],
            'sexe' => $getContent['sexe'],
            'urgence' => (string) $getContent['urgence'],
            'urgenceContact' => $getContent['urgenceContact']
        ];

        $request->getSession()->set('identite', $identite);

        return new JsonResponse($identite, Response::HTTP_OK);
    }
}