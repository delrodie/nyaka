<?php

namespace App\Controller;

use App\Service\AllRepositories;
use App\Service\Gestion;
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
        private HttpClientInterface $httpClient
    )
    {
    }

    #[Route('/{matricule}', name: 'app_recu_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        $aspirant = $this->allRepositories->getOneAspirant(null, $matricule);

        if ($aspirant){
            dd($this->wave($aspirant));
        }
        return $this->render('frontend/recu_search.html.twig',[
            'aspirant' => $aspirant,
        ]);
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
            $message =  "HTTP Error ".$response->getStatusCode();
        }else{
            $message = $response->toArray();
        }

        return $message;
    }
}