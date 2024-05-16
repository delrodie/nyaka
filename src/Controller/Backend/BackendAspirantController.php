<?php

namespace App\Controller\Backend;

use App\Entity\Aspirant;
use App\Form\AspirantType;
use App\Repository\AspirantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/aspirant')]
class BackendAspirantController extends AbstractController
{
    #[Route('/', name: 'app_backend_aspirant_index', methods: ['GET'])]
    public function index(AspirantRepository $aspirantRepository): Response
    {
        return $this->render('backend_aspirant/index.html.twig', [
            'aspirants' => $aspirantRepository->getAllByStatusCompletedOrNot(),
        ]);
    }

    #[Route('/new', name: 'app_backend_aspirant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aspirant = new Aspirant();
        $form = $this->createForm(AspirantType::class, $aspirant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aspirant);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_aspirant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_aspirant/new.html.twig', [
            'aspirant' => $aspirant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_aspirant_show', methods: ['GET'])]
    public function show(Aspirant $aspirant): Response
    {
        return $this->render('backend_aspirant/show.html.twig', [
            'aspirant' => $aspirant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_aspirant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aspirant $aspirant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AspirantType::class, $aspirant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recu_show', [
                'matricule' => $aspirant->getMatricule()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_aspirant/edit.html.twig', [
            'aspirant' => $aspirant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_aspirant_delete', methods: ['POST'])]
    public function delete(Request $request, Aspirant $aspirant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aspirant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($aspirant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_aspirant_index', [], Response::HTTP_SEE_OTHER);
    }
}
