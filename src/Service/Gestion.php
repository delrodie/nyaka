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
     * Generation de code de Doyenne
     *
     * @param $vicariat
     * @return false|string
     */
    public function codeDoyenne($vicariat): bool|string
    {
        $lastDoyenne = $this->allRepositories->getOneDoyenne();

        if (!$vicariat) return false;

        if (!$lastDoyenne) return (int) $vicariat->getCode() .''. 10;

        $suffixe = substr($lastDoyenne->getCode(), -2);

        return (int) $vicariat->getCode().''.(int) $suffixe + 1;
    }

    /**
     * Mise a jour du code de doyenne
     * 
     * @param $vicariat
     * @param $doyenne
     * @return string
     */
    public function updateCodeDoyenne($vicariat, $doyenne): string
    {
        return (int) $vicariat->getCode().''.substr($doyenne->getCode(), -2);
    }

    /**
     * L'unicité de doyenne
     *
     * @param $slug
     * @return bool
     */
    public function uniciteDoyenne($slug): bool
    {
        $exist = $this->allRepositories->getOneDoyenne($slug);
        if (!$exist) return false;
        return true;
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