<?php

namespace JA\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('version')
            ->add('platforms')
            ->add('about')
            ->add('download')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JA\GameBundle\Entity\Game'
        ));
    }

    public function getName()
    {
        return 'ja_gamebundle_gametype';
    }
}
