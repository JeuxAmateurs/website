<?php

namespace JA\AppBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use JA\AppBundle\Form\DataTransformer\TechnologiesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TechnologiesType extends AbstractType
{
    /** @var ObjectManager */
    private $om;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TechnologiesTransformer($this->om);

        $builder->addModelTransformer($transformer);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'technologies';
    }
}
