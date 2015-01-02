<?php

namespace JA\AppBundle\EventListener;

use Ornicar\GravatarBundle\GravatarApi;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use JA\AppBundle\Model\AvatarInterface;

/**
 * Class AvatarListener
 * @package JA\AppBundle\EventListener
 *
 * This class is used to replace avatars by Gravatar if it doesn't exist
 */
class AvatarListener
{
    private $gravatarApi;

    public function __construct(GravatarApi $gravatarApi)
    {
        $this->gravatarApi = $gravatarApi;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if($object instanceof AvatarInterface)
        {
            if(!$object->getAvatar()) {
                $object->setAvatar($this->gravatarApi->getUrl($object->getEmail()));
            }
        }
    }
}
