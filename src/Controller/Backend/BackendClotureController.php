<?php

namespace App\Controller\Backend;

use App\Entity\Cloture;
use App\Form\ClotureType;
use App\Repository\ClotureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/cloture')]
class BackendClotureController extends AbstractController
{
    #[Route('/', name: 'app_backend_cloture_index', methods: ['GET'])]
    public function index(ClotureRepository $clotureRepository): Response
    {
        return $this->render('backend_cloture/index.html.twig', [
            'clotures' => $clotureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_cloture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClotureRepository $clotureRepository): Response
    {
        // Si la cloture existe deja alors passer à la modification
        $exist = $clotureRepository->findOneBy([],['id' => "DESC"]);
        if ($exist) {
            return $this->redirectToRoute('app_backend_cloture_edit', [
                'id' => $exist->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        $cloture = new Cloture();
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($cloture);
            $entityManager->persist($cloture);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_cloture_edit', [
                'id' => $cloture->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_cloture/new.html.twig', [
            'cloture' => $cloture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_cloture_show', methods: ['GET'])]
    public function show(Cloture $cloture): Response
    {
        return $this->render('backend_cloture/show.html.twig', [
            'cloture' => $cloture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_cloture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cloture $cloture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_cloture_edit', [
                'id' => $cloture->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_cloture/edit.html.twig', [
            'cloture' => $cloture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_cloture_delete', methods: ['POST'])]
    public function delete(Request $request, Cloture $cloture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cloture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cloture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_cloture_index', [], Response::HTTP_SEE_OTHER);
    }
}
