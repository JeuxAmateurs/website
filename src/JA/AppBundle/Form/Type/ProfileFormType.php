<?php

namespace JA\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    const NAME = 'profile';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('biography')
                ->add('skills', 'skill', array(
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return self::NAME;
    }
}