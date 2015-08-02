<?php

namespace JA\AppBundle\Security;

use JA\AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class GameVoter extends AbstractVoter
{
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT   = 'edit';
    const DELETE   = 'delete';
    const FAVORITE   = 'favorite';

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
        return array(self::VIEW, self::CREATE, self::EDIT, self::DELETE, self::FAVORITE);
    }

    protected function getSupportedClasses()
    {
        return array('JA\AppBundle\Entity\Game');
    }

    /**
     * @param string|object|null $object
     * @return bool
     */
    public function supportsClass($object)
    {
        if(is_string($object))
            return parent::supportsClass($object);

        return parent::supportsClass(get_class($object));
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$object || !$this->supportsClass($object)) {
            return self::ACCESS_ABSTAIN;
        }

        $this->token = $token;

        // All users can view the game currently (maybe there will be some publish options or private projects in the future)
        if(in_array(self::VIEW, $attributes, true))
            return self::ACCESS_GRANTED;

        if(!$this->token->getUser() instanceof User) {
            if(null !== $this->logger)
                $this->logger->debug('User is not an instance of class', array('class' => 'JA\AppBundle\Entity\User'));
            return self::ACCESS_DENIED;
        }

        if(null !== $this->logger)
            $this->logger->debug('Invoking game voter');

        // abstain vote by default in case none of the attributes are supported
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            // as soon as at least one attribute is supported, default is to deny access
            $vote = self::ACCESS_DENIED;

            // No need for object here
            if($attribute === self::FAVORITE)
                return self::ACCESS_GRANTED;

            if($attribute === self::CREATE
                && !in_array('ROLE_BANNED', $token->getUser()->getRoles(), true))
                return self::ACCESS_GRANTED;

            if ($this->isGranted($attribute, $object, $token->getUser())) {
                // grant access as soon as at least one voter returns a positive response
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    protected function isGranted($attribute, $game, $user = null)
    {
        if(!$user || is_string($game))
            return false;

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
