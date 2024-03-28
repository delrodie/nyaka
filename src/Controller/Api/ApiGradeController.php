<?php

namespace App\Controller\Api;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/grade')]
class ApiGradeController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/', name: "app_api_grade_show", methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $gradeRequest = (int) $request->get('grade_id');

        $grade = $this->allRepositories->getOneGrade(null, $id = $gradeRequest);

        $jsonGrade = $this->serializer->serialize($grade, 'json', [
            'groups' => 'participation',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new JsonResponse($jsonGrade, Response::HTTP_OK, [], true);
    }
}