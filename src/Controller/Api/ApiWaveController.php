<?php

namespace App\Controller\Api;

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
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/', name: 'app_api_wave_checkout', methods: ['POST'])]
    public function checkout(Request $request)
    {
        $data = json_decode($request->getContent(), true);
//        dd($request->getContent());

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

//            $jsonResponse = $this->serializer->serialize($response, 'json', []);

//            dd($this->json($response));

            return $this->json($response->getContent());
        } catch (\Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}