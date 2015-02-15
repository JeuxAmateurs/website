<?php

namespace JA\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SkillType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'JAAppBundle:Skill',
            'property' => 'name',
//            'query_builder' => function(EntityRepository $er) {
//                return $er->createQueryBuilder('s')
//                    ->orderBy('s.name', 'ASC');
//            },
        ));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'skill';
    }
}