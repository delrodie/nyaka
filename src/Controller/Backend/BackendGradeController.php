<?php

namespace App\Controller\Backend;

use App\Entity\Grade;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use App\Service\AllRepositories;
use App\Service\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/grade')]
class BackendGradeController extends AbstractController
{
    public function __construct(
        private Gestion $gestion,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_grade_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, GradeRepository $gradeRepository): Response
    {
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade->setSlug($this->gestion->slug($grade->getNom()));

            $entityManager->persist($grade);
            $entityManager->flush();

            sweetalert()->addSuccess("La grade {$grade->getNom()} a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_grade_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_grade/index.html.twig', [
            'grades' => $gradeRepository->findAll(),
            'grade' => $grade,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_grade_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_grade_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_grade/new.html.twig', [
            'grade' => $grade,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_grade_show', methods: ['GET'])]
    public function show(Grade $grade): Response
    {
        return $this->render('backend_grade/show.html.twig', [
            'grade' => $grade,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_grade_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Grade $grade, EntityManagerInterface $entityManager, GradeRepository $gradeRepository): Response
    {
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_grade_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_grade/edit.html.twig', [
            'grades' => $gradeRepository->findAll(),
            'grade' => $grade,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_grade_delete', methods: ['POST'])]
    public function delete(Request $request, Grade $grade, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->request->get('_token'))) {
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_grade_index', [], Response::HTTP_SEE_OTHER);
    }
}
