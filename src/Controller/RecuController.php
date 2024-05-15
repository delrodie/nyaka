<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/recu')]
class RecuController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories,
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/{matricule}', name: 'app_recu_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        $aspirant = $this->allRepositories->getOneAspirant(null, $matricule);

        if ($aspirant && $aspirant->getWaveCheckoutStatus() !== 'complete'){

            $wave = $this->wave($aspirant);
            if ($wave !== true) return new Response ($wave);
        }
        return $this->render('frontend/recu_search.html.twig',[
            'aspirant' => $aspirant,
        ]);
    }

    #[Route('/checkin/{matricule}', name: 'app_recu_checkin', methods: ['GET'])]
    public function checkin($matricule)
    {
        $aspirant = $this->allRepositories->getOneAspirant(null, $matricule);

        if ($aspirant && $aspirant->getWaveCheckoutStatus() !== 'complete'){

            $this->wave($aspirant);
        }

        return $this->redirectToRoute('app_backend_aspirant_show',['id' => $aspirant->getId()]);
    }

    public function wave($aspirant)
    {
        $response = $this->httpClient->request(
            'GET',
            "https://api.wave.com/v1/checkout/sessions/{$aspirant->getWaveId()}",[
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getParameter('wave_api_key'),
                ],
            'timeout' => 5
            ]
        );

        if ($response->getStatusCode() !== 200){
            return  "HTTP Error ".$response->getStatusCode();
        }

        $data = $response->toArray();

        if ($data['checkout_status'])
            $aspirant->setWaveCheckoutStatus($data['checkout_status']);
        if ($data['payment_status'])
            $aspirant->setWavePaymentStatus($data['payment_status']);
        if ($data['when_completed'])
            $aspirant->setWaveWhenCompleted($data['when_completed']);
        if ($data['transaction_id'])
            $aspirant->setWaveTransactionId($data['transaction_id']);

        $this->entityManager->flush();

        return true;
    }
}