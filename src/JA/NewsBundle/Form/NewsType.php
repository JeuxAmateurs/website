<?php

namespace JA\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
			->add('games', 'entity', array('class' => 'JAGameBundle:Game', 'property' => 'title', 'multiple' => true, 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JA\NewsBundle\Entity\News'
        ));
    }

    public function getName()
    {
        return 'ja_newsbundle_newstype';
    }
}
