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
            ->add('slug')
            ->add('content')
            ->add('createdAt')
            ->add('updatedAt')
			->add('games', 'entity', array('class' => 'JAGameBundle:Game', 'property' => 'title', 'multiple' => true))
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
