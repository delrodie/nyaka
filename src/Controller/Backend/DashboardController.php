<?php

namespace App\Controller\Backend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_dashboard')]
    public function index(): Response
    {
        return $this->render('backend/dashboard.html.twig',[
            'grades' => $this->allRepositories->getAspirantByAllGrade('complete'),
            'finance' => $this->allRepositories->getMontantTotalParticipation(),
            'aspirants' => $this->allRepositories->getAllAspirantByStatus('complete', 'DESC'),
            'vicariats' => $this->allRepositories->getAspirantsByVicariat('complete'),
        ]);
    }
}