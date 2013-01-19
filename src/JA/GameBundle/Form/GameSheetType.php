<?php

namespace JA\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameSheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('developer', text)
			->add('projectName', text)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JA\GameBundle\Entity\GameSheet'
        ));
    }

    public function getName()
    {
        return 'ja_gamebundle_gamesheettype';
    }
}
