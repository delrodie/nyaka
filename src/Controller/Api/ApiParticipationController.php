<?php

namespace App\Controller\Api;

use App\Entity\Aspirant;
use App\Service\AllRepositories;
use App\Service\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/participation')]
class ApiParticipationController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private HttpClientInterface $httpClient
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

    #[Route('/paiement/validation', name: "app_api_participation_paiement", methods: ['POST'])]
    public function paiement(Request $request): JsonResponse
    {
        $getContent = json_decode($request->getContent(), true);

        $session = $request->getSession();
        $identite = $session->get('identite');
        $base = $session->get('base');

        $grade = [
            'grade' => $getContent['grade'],
//            'teeshirt' => $getContent['teeshirt'],
            'taille' => $getContent['taille'],
            'montant' => $getContent['montant']
        ];

        $gradeEntity = $this->allRepositories->getOneGrade(null, (int) $grade['grade']);
        $sectionEntity = $this->allRepositories->getOneSection(null, (int) $base['section']);

        // Verification de la non existence de l'aspirant dans le système
        $exist = $this->allRepositories->verifAspirant($identite['nom'], $identite['prenom'], $identite['contact']);
        if ($exist){
            sweetalert()->addError("Echèc! Ce participant a déjà été enregistré dans le système.");
            return new JsonResponse('Echec', Response::HTTP_OK,[], true);
        }

        $aspirant = new Aspirant();
        $aspirant->setMatricule($this->gestion->matricule((int) $base['vicariat']));
        $aspirant->setNom($identite['nom']);
        $aspirant->setPrenoms($identite['prenom']);
        $aspirant->setSexe($identite['sexe']);
        $aspirant->setContact($identite['contact']);
        $aspirant->setUrgence($identite['urgence']);
        $aspirant->setContactUrgence($identite['urgenceContact']);
        $aspirant->setMontant($grade['montant']);
//        $aspirant->setTeeshirt($grade['teeshirt']);
        $aspirant->setTaille($grade['taille']);
        $aspirant->setMontantTeeshirt($gradeEntity->getTeeshirt());
        $aspirant->setGrade($gradeEntity);
        $aspirant->setSection($sectionEntity);

        $this->entityManager->persist($aspirant);
        $this->entityManager->flush();

        $session->set('base', '');
        $session->set('identite', '');
        $session->set('aspirant', $aspirant->getMatricule());

        $jsonAspirant = $this->serializer->serialize($aspirant, 'json', [
            'groups' => 'participation',
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

//        $response = $this->wave($aspirant->getMatricule(), $aspirant->getMontant());

        return new JsonResponse($jsonAspirant, Response::HTTP_CREATED, [], true);
    }

}