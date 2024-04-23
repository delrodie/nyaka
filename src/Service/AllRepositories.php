<?php

namespace App\Service;

use App\Entity\Aspirant;
use App\Repository\AspirantRepository;
use App\Repository\DoyenneRepository;
use App\Repository\GradeRepository;
use App\Repository\SectionRepository;
use App\Repository\VicariatRepository;
use phpDocumentor\Reflection\Types\String_;

class AllRepositories
{
    public function __construct(
        private VicariatRepository $vicariatRepository,
        private DoyenneRepository $doyenneRepository,
        private SectionRepository $sectionRepository,
        private GradeRepository $gradeRepository,
        private AspirantRepository $aspirantRepository
    )
    {
    }

    public function getOneVicariat(string $slug = null, int $id = null)
    {
        if ($slug){
            return $this->vicariatRepository->findOneBy(['slug' => $slug]);
        }

        if ($id) {
            return $this->vicariatRepository->findOneBy(['id' => $id]);
        }

        return $this->vicariatRepository->findOneBy([],['id' => "DESC"]);
    }

    /**
     * Liste des vicariats
     *
     * @param $order
     * @return \App\Entity\Vicariat[]
     */
    public function getAllVicariat($order = null)
    {
        if ($order){
            return $this->vicariatRepository->findBy([],['nom' => $order]);
        }

        return $this->vicariatRepository->findAll();
    }

    public function getOneDoyenne(string $slug=null, int $id=null)
    {
        if ($slug) return $this->doyenneRepository->findOneBy(['slug' => $slug]);
        if ($id) return $this->doyenneRepository->findOneBy(['id' => $id]);

        return $this->doyenneRepository->findOneBy([],['id' => "DESC"]);
    }

    public function getAllDoyenne($vicariat=null, string $order=null)
    {
        if ($vicariat) return $this->doyenneRepository->findBy(['vicariat' => $vicariat], ['nom' => "ASC"]);

        if ($order) return $this->doyenneRepository->findBy([],['nom' => $order]);

        return $this->doyenneRepository->findAll();
    }

    public function getOneSection(string $slug=null, int $id=null)
    {
        if ($slug) return $this->sectionRepository->findOneBy(['slug' =>$slug]);
        if ($id) return $this->sectionRepository->findOneBy(['id' => $id]);

        return $this->sectionRepository->findOneBy([],['id' => "DESC"]);
    }

    public function getAllSection($doyenne=null, string $order=null)
    {
        if ($doyenne) return $this->sectionRepository->findBy(['doyenne' => $doyenne], ['paroisse' => "ASC"]);
        if ($order) return $this->sectionRepository->findBy([], ['paroisse' => $order]);

        return $this->sectionRepository->findAll();
    }

    /**
     *
     * @param $slug
     * @param $id
     * @return \App\Entity\Grade|null
     */
    public function getOneGrade($slug=null, $id=null)
    {
        if ($slug) return $this->gradeRepository->findOneBy(['slug' => $slug]);
        if ($id) return $this->gradeRepository->findOneBy(['id' => $id]);

        return $this->gradeRepository->findOneBy([],['id' => "DESC"]);
    }

    /**
     * Liste des grades
     *
     * @param string|null $order
     * @return \App\Entity\Grade[]
     */
    public function getAllGrade(string $order=null): array
    {
        if ($order) return $this->gradeRepository->findBy([],['nom' => $order]);

        return $this->gradeRepository->findAll();
    }

    /**
     *
     * @param $id
     * @param string|null $matricule
     * @return \App\Entity\Aspirant|null
     */
    public function getOneAspirant($id = null, string $matricule = null): ?Aspirant
    {
        if ($id) return $this->aspirantRepository->findOneBy(['id' => $id]);
        if ($matricule) return $this->aspirantRepository->findOneBy(['matricule' => $matricule]);

        return $this->aspirantRepository->findOneBy([],['id' => $id]);
    }

    /**
     * Verification de l'existence de l'aspirant dans le système
     *
     * @param $nom
     * @param $prenom
     * @param $contact
     * @return bool
     */
    public function verifAspirant($nom, $prenom, $contact): bool
    {
        $aspirant = $this->aspirantRepository->findOneBy([
            'nom' => $nom,
            'prenoms' => $prenom,
            'contact' => $contact
        ]);

        if ($aspirant) return true;

        return false;
    }

    public function getAspirantByGrade()
    {
        $grades = $this->getAllGrade();

        $liste=[]; $i=0;
        foreach ($grades as $grade){
            $liste[$i++] = [
                'grade' => $grade,
                'aspirants' => $this->aspirantRepository->getAllByGrade($grade->getId())
            ];
        }

        return $liste;
    }

    public function getMontantTotalParticipation()
    {
        return $this->aspirantRepository->getMontantTotal();
    }
}