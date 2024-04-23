<?php

namespace App\Controller\Backend;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use App\Service\AllRepositories;
use App\Service\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/section')]
class BackendSectionController extends AbstractController
{
    public function __construct(
        private Gestion $gestion, private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_section_index', methods: ['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, SectionRepository $sectionRepository): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->gestion->slug($section->getParoisse());
            if ($this->gestion->uniciteSection($slug)){
                sweetalert()->addWarning("Attention, cette section existe deja dans le système!");

                return $this->redirectToRoute('app_backend_section_index');
            }

            $section->setSlug($slug);
            $section->setCode($this->gestion->codeSection($section->getDoyenne()));

            $entityManager->persist($section);
            $entityManager->flush();

            sweetalert()->addSuccess("La section {$section->getParoisse()} a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
            'section' => $section,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_section/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_section_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        return $this->render('backend_section/show.html.twig', [
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager, SectionRepository $sectionRepository): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setSlug($this->gestion->slug($section->getSlug()));
            $section->setCode($this->gestion->updateCodeSection($section));

            $entityManager->flush();

            sweetalert()->addSuccess("La section {$section->getParoisse()} a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_section/edit.html.twig', [
            'sections' => $sectionRepository->findAll(),
            'section' => $section,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
