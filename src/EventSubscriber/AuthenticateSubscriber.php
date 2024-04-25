<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class AuthenticateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
       $securityToken = $event->getAuthenticationToken();
       $userIdentiy = $this->getUserIdentify($securityToken);

       // Mise a jour de la ligne utilisateur
        $userEntity = $this->userRepository->findOneBy(['username' => $userIdentiy]);
        if ($userEntity){
            $userEntity->setConnexion((int)$userEntity->getConnexion()+1);
            $userEntity->setLastConnectedAt(new \DateTime());

            $this->entityManager->flush();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }

    private function getUserIdentify(TokenInterface $securityToken): string
    {
        return $securityToken->getUserIdentifier();
    }
}
