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

    public function codeSection($doyenne)
    {
        $lastSection = $this->allRepositories->getOneDoyenne();
        if (!$doyenne) return false;
        if (!$lastSection) return (int) $doyenne->getCode().''. 100;

        $suffixe = substr($lastSection->getCode(), -3);

        return (int) $doyenne->getCode().''.(int) $suffixe + 1;
    }

    /**
     * La mise a jour du code de la section
     *
     * @param $section
     * @return string
     */
    public function updateCodeSection($section): string
    {
        return (int) $section->getDoyenne()->getCode().''.substr($section->getCode(), -3);
    }

    public function uniciteSection($slug)
    {
        $exist = $this->allRepositories->getOneSection($slug);
        if ($exist) return true;

        return false;
    }

    /**
     * Génération du matricule du participant
     *
     * @param $vicariat
     * @return string
     * @throws \Random\RandomException
     */
    public function matricule($vicariat): string
    {
        $vicariatEntity = $this->allRepositories->getOneVicariat(null, $vicariat);

        $nombreAleatoire = random_int(10000,99999);

        $lettreAleatoire = chr(rand(65, 90));

        return $vicariatEntity->getCode().''.$nombreAleatoire.''.$lettreAleatoire;
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