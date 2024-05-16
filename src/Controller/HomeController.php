<?php

namespace App\Controller;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private AllRepositories $allRepositories)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        if ($this->allRepositories->getClotureStatut())
            return $this->redirectToRoute('app_cloture');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/cloture', name: 'app_cloture')]
    public function cloture()
    {
        if (!$this->allRepositories->getClotureStatut())
            return  $this->redirectToRoute('app_home');

        return $this->render('frontend/cloture.html.twig');
    }
}
