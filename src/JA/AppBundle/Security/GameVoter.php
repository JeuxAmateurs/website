<?php

namespace JA\AppBundle\Security;

use JA\AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class GameVoter extends AbstractVoter
{
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT   = 'edit';
    const DELETE   = 'delete';

    private $roleHierarchy;
    private $logger;

    private $token;

    public function __construct(roleHierarchy $roleHierarchy, LoggerInterface $logger = null)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->logger = $logger;
    }

    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::CREATE, self::EDIT, self::DELETE);
    }

    protected function getSupportedClasses()
    {
        return array('JA\AppBundle\Entity\Game');
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $this->token = $token;

        if(!$this->token->getUser() instanceof User) {
            if(null !== $this->logger)
                $this->logger->debug('User is not an instance of class', array('class' => 'JA\AppBundle\Entity\User'));
            return self::ACCESS_DENIED;
        }

        if(null !== $this->logger)
            $this->logger->debug('Invoking game voter');

        // No need for object here
        if(in_array(self::CREATE, $attributes, true)
            && !in_array('ROLE_BANNED', $token->getUser()->getRoles(), true))
            return self::ACCESS_GRANTED;

        return parent::vote($token, $object, $attributes);
    }

    protected function isGranted($attribute, $game, $user = null)
    {
        // Using this to know hierarchy
        $roleHierarchyVoter = new RoleHierarchyVoter($this->roleHierarchy);
        $adminAccess = $roleHierarchyVoter->vote($this->token, null, array('ROLE_ADMIN'));

        // Admin and owner can do anything
        if($adminAccess === VoterInterface::ACCESS_GRANTED
            || $user->getId() === $game->getOwner()->getId())
            return true;

        if(null !== $this->logger)
            $this->logger->debug('User is not an admin nor the game\'s owner');

        // Moderators can edit
        if($attribute === self::EDIT)
        {
            $modoAccess = $roleHierarchyVoter->vote($this->token, null, array('ROLE_MODERATOR'));
            if($modoAccess === VoterInterface::ACCESS_GRANTED)
                return true;

//            if(in_array($game->getTeam(), $user->getTeams(), true))
//                return true;
        }

        if(null !== $this->logger)
            $this->logger->debug('User is not a moderator nor part of the team');

        return false;
    }
}