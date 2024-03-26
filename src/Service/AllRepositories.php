<?php

namespace App\Service;

use App\Repository\DoyenneRepository;
use App\Repository\SectionRepository;
use App\Repository\VicariatRepository;
use phpDocumentor\Reflection\Types\String_;

class AllRepositories
{
    public function __construct(
        private VicariatRepository $vicariatRepository,
        private DoyenneRepository $doyenneRepository,
        private SectionRepository $sectionRepository
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
}