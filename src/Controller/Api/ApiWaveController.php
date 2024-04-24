<?php

namespace App\Controller\Api;

use App\Service\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/wave')]
class ApiWaveController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_api_wave_checkout', methods: ['POST'])]
    public function checkout(Request $request)
    {
//        $aspirant = $request->getSession()->get('aspirant');
        $data = json_decode($request->getContent(), true);
//        dd($data['success_url']);

        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.wave.com/v1/checkout/sessions',[
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getParameter('wave_api_key'),
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            if ($response->getStatusCode() === 200){
                $content = json_decode($response->getContent());
                $matricule = basename($data['success_url']);

                $aspirant = $this->allRepositories->getOneAspirant(null, $matricule);

                if ($aspirant){
                    $aspirant->setWaveId($content->id);
                    $aspirant->setWaveCheckoutStatus($content->checkout_status);
                    $aspirant->setWaveClientReference($content->client_reference);
                    $aspirant->setWavePaymentStatus($content->payment_status);
                    $aspirant->setWaveWhenCompleted($content->when_completed);
                    $aspirant->setWaveWhenCreated($content->when_created);

                    $this->entityManager->flush();
                }
            }

            return $this->json($response);
        } catch (\Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}