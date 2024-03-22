<?php

namespace App\Service;

use App\Repository\VicariatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Gestion
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AllRepositories $allRepositories
    )
    {
    }

    /**
     * Generation du code du Vicariat
     *
     * @return int
     */
    public function codeVicariat(): int
    {
        $lastVicariat = $this->allRepositories->getOneVicariat();
        if (!$lastVicariat) return 1;

        return (int) $lastVicariat->getCode() + 1;
    }

    /**
     * Verification de l'unicité du vicariat
     * @param $slug
     * @return bool
     */
    public function uniciteVicariat($slug): bool
    {
        $exist = $this->allRepositories->getOneVicariat($slug);

        if ($exist) return true;
        return false;
    }

    /**
     * Formattage slug
     * @param string $string
     * @return \Symfony\Component\String\AbstractUnicodeString
     */
    public function slug(string $string): AbstractUnicodeString
    {
        return (new AsciiSlugger())->slug(strtolower($string));
    }
}