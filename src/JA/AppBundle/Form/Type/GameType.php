<?php

namespace JA\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    const NAME = 'game';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('technologies', 'entity', array(
                'class' => 'JAAppBundle:Technology',
                'property' => 'name',
                'multiple' => 'true',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'JA\AppBundle\Entity\Game',
                'csrf_protection' => false,
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }
}
