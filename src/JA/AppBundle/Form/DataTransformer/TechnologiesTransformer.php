<?php

namespace JA\AppBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use JA\AppBundle\Entity\Technology;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Coming from http://blogsh.de/2012/03/04/using-a-datatransformer-to-save-tags-for-an-object-in-symfony/
 */
class TechnologiesTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms the Entities to a value for the form field
     *
     * @param array $tags
     * @return string
     */
    public function transform($tags)
    {
        if (!$tags) {
            $tags = new ArrayCollection();
        }

        $tags = $tags->toArray();
        /** @var Technology $tag */
        foreach ($tags as $key => $tag) {
            $tags[$key] = $tag->getName();
        }

        return implode(', ', $tags);
    }

    /**
     * Transforms the value the users has typed to a value that suits the field
     *
     * @param string $tags
     * @return array
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            $tags = '';
        }

        $arrayTags = array_filter(array_map('trim', explode(',', $tags)));

        // Getting all the existing tags from the database
        $existingTags = $this->om->getRepository('JAAppBundle:Technology')
            ->findByName($arrayTags);

        // for each existing tag, the element is removed from the first array
        /** @var Technology $tag */
        foreach($existingTags as $tag)
        {
            foreach($arrayTags as $key => $value)
            {
                if(strtolower($tag->getName()) === strtolower($value))
                    unset($arrayTags[$key]);
            }
        }

        // Creation of the new tags
        foreach($arrayTags as $key => $tag)
            $arrayTags[$key] = new Technology($tag);

        return array_merge($existingTags, $arrayTags);
    }
}