<?php

namespace App\Controller\Api;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/doyenne')]
class ApiDoyenneController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/', name: 'app_api_doyenne_list', methods: ['GET'])]
    public function list(Request $request)
    {
        $vicariatRequest = $request->get('vicariat_id');

        $doyennes = $this->allRepositories->getAllDoyenne($vicariatRequest);

        $jsonDoyenne = $this->serializer->serialize($doyennes, 'json', [
            'groups' => 'participation',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        
        return new JsonResponse($jsonDoyenne, Response::HTTP_OK, [], true);
    }
}