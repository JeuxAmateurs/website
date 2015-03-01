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

class TechnologyVoter extends AbstractVoter
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
        return array('JA\AppBundle\Entity\Technology');
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

        // All users can view the technology
        if(in_array(self::VIEW, $attributes, true))
            return self::ACCESS_GRANTED;

        if(!$this->token->getUser() instanceof User) {
            if(null !== $this->logger)
                $this->logger->debug('User is not an instance of class', array('class' => 'JA\AppBundle\Entity\User'));
            return self::ACCESS_DENIED;
        }

        if(null !== $this->logger)
            $this->logger->debug('Invoking technology voter');

        // abstain vote by default in case none of the attributes are supported
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            // as soon as at least one attribute is supported, default is to deny access
            $vote = self::ACCESS_DENIED;

            // No need for object here
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
        if(!$user)
            return false;

        // Using this to know hierarchy
        $roleHierarchyVoter = new RoleHierarchyVoter($this->roleHierarchy);
        $adminAccess = $roleHierarchyVoter->vote($this->token, null, array('ROLE_ADMIN'));
        $modoAccess = $roleHierarchyVoter->vote($this->token, null, array('ROLE_MODERATOR'));

        // Admin and moderator can do anything
        if($adminAccess === VoterInterface::ACCESS_GRANTED
            || $modoAccess === VoterInterface::ACCESS_GRANTED)
            return true;

        // All users can edit (except banned)
        if($attribute === self::EDIT)
        {
            if(in_array('ROLE_USER', $user->getRoles(), true)
            && !in_array('ROLE_BANNED', $user->getRoles(), true))
                return true;
        }

        return false;
    }
}
