<?php

namespace App\Controller\Api;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/section')]
class ApiSectionController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/', name: 'app_api_section_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $doyenneRequest = $request->get('doyenne_id');

        $sections = $this->allRepositories->getAllSection($doyenneRequest);

        $jsonSections = $this->serializer->serialize($sections, 'json',[
            'groups' => 'participation',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new JsonResponse($jsonSections, Response::HTTP_OK, [], true);
    }
}