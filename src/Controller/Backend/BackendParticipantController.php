<?php

namespace App\Controller\Backend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backend/participant')]
class BackendParticipantController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_participant_confirme')]
    public function confirme()
    {
        return $this->render('backend/participant_complete.html.twig',[
            'participants' => $this->allRepositories->getAllAspirantByStatus('complete')
        ]);
    }

    #[Route('/{filtre}', name: 'app_backend_participant_filtre', methods: ['GET'])]
    public function filtre(Request $request, $filtre): Response
    {
        return match ($filtre){
            'grade' => $this->gradeFiltre($request),
            'vicariat' => $this->vicariatFiltre($request),
            'doyenne' => $this->doyenneFiltre($request),
            'section' => $this->sectionFiltre($request),
            default => $this->redirectToRoute('app_backend_participant_confirme',[], Response::HTTP_SEE_OTHER)
        };
    }

    private function gradeFiltre(Request $request): Response
    {
        $gradeID = $request->get('gradeID');

        if ($gradeID) {
            $grade = $this->allRepositories->getOneGrade(null, $gradeID);
            $participants = $this->allRepositories->getAllAspirantByGradeID((int)$gradeID);
        }else{
            sweetalert()->addInfo("Veuillez selectionner le grade pour voir ses participants");
            $participants = [];
            $grade = [];
        }

        return $this->render('backend/participant_grade.html.twig',[
            'participants' => $participants,
            'grades' => $this->allRepositories->getAllGrade(),
            'grade' => $grade
        ]);
    }

    private function vicariatFiltre(Request $request): Response
    {
        $vicariatID = $request->get('vicariatID');

        if ($vicariatID){
            $vicariat = $this->allRepositories->getOneVicariat(null, $vicariatID);
            $participants = $this->allRepositories->getAllAspirantByVicariatID($vicariatID);
        }else{
            sweetalert()->addInfo("Veuillez selectionner le vicariat pour voir ses participants");
            $participants = [];
            $vicariat = [];
        }

        return $this->render('backend/participant_vicariat.html.twig',[
            'participants' => $participants,
            'vicariats' => $this->allRepositories->getAllVicariat(),
            'vicariat' => $vicariat
        ]);
    }

    private function doyenneFiltre(Request $request): Response
    {
        $doyenneID = $request->get('DoyenneID');

        if ($doyenneID){
            $doyenne = $this->allRepositories->getOneDoyenne(null, $doyenneID);
            $participants = $this->allRepositories->getAllAspirantByDoyenneID($doyenneID);
        }else{
            sweetalert()->addInfo("Veuillez selectionner le doyenne pour voir ses participants");
            $participants = [];
            $doyenne = [];
        }

        return $this->render('backend/participant_doyenne.html.twig',[
            'participants' => $participants,
            'doyennes' => $this->allRepositories->getAllDoyenne(),
            'doyenne' => $doyenne
        ]);
    }

    private function sectionFiltre(Request $request)
    {
        $sectionID = $request->get('sectionID');

        if ($sectionID){
            $section = $this->allRepositories->getOneSection(null, $sectionID);
            $participants = $this->allRepositories->getAllAspirantBySectionID($sectionID);
        }else{
            sweetalert()->addInfo("Veuillez selectionner la section pour voir ses participants");
            $participants = [];
            $section = [];
        }

        return $this->render('backend/participant_section.html.twig',[
            'participants' => $participants,
            'sections' => $this->allRepositories->getAllSection(),
            'section' => $section
        ]);
    }


}