<?php

namespace App\Service;

use App\Repository\VicariatRepository;

class AllRepositories
{
    public function __construct(
        private VicariatRepository $vicariatRepository
    )
    {
    }

    public function getOneVicariat($slug = null)
    {
        if ($slug){
            return $this->vicariatRepository->findOneBy(['slug' => $slug]);
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
}