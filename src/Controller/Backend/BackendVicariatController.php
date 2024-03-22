<?php

namespace App\Controller\Backend;

use App\Entity\Vicariat;
use App\Form\VicariatType;
use App\Repository\VicariatRepository;
use App\Service\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/vicariat')]
class BackendVicariatController extends AbstractController
{
    public function __construct(
        private Gestion $gestion
    )
    {
    }

    #[Route('/', name: 'app_backend_vicariat_index', methods: ['GET', 'POST'])]
    public function index(Request $request, VicariatRepository $vicariatRepository, EntityManagerInterface $entityManager): Response
    {
        $vicariat = new Vicariat();
        $form = $this->createForm(VicariatType::class, $vicariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->gestion->slug($vicariat->getNom());
            $vicariat->setSlug($slug);
            $vicariat->setCode($this->gestion->codeVicariat());

            // Vérification de l'unicité
            if ($this->gestion->uniciteVicariat($slug)){
                sweetalert()->addError("Attention, ce vicariat a déjà été ajouté!");
                return $this->redirectToRoute('app_backend_vicariat_index',[], Response::HTTP_SEE_OTHER);
            }

            $entityManager->persist($vicariat);
            $entityManager->flush();

            sweetalert()->addSuccess("Le vicariat {$vicariat->getNom()} a été ajouté avec succès!");

            return $this->redirectToRoute('app_backend_vicariat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_vicariat/index.html.twig', [
            'vicariats' => $vicariatRepository->findAll(),
            'vicariat' => $vicariat,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_vicariat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vicariat = new Vicariat();
        $form = $this->createForm(VicariatType::class, $vicariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vicariat);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_vicariat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_vicariat/new.html.twig', [
            'vicariat' => $vicariat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_vicariat_show', methods: ['GET'])]
    public function show(Vicariat $vicariat): Response
    {
        return $this->render('backend_vicariat/show.html.twig', [
            'vicariat' => $vicariat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_vicariat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vicariat $vicariat, VicariatRepository $vicariatRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VicariatType::class, $vicariat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $vicariat->setSlug($this->gestion->slug($vicariat->getNom()));
            $entityManager->flush();

            sweetalert()->addSuccess("Le vicariat {$vicariat->getNom()} a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_vicariat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_vicariat/edit.html.twig', [
            'vicariats' => $vicariatRepository->findAll(),
            'vicariat' => $vicariat,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_vicariat_delete', methods: ['POST'])]
    public function delete(Request $request, Vicariat $vicariat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vicariat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vicariat);
            $entityManager->flush();

            sweetalert()->addSuccess("Le vicariat {$vicariat->getNom()} a été supprimé avec succès!");
        }

        return $this->redirectToRoute('app_backend_vicariat_index', [], Response::HTTP_SEE_OTHER);
    }
}
