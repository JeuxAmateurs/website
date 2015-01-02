<?php

namespace JA\AppBundle\Exception;

use Symfony\Component\Form\FormInterface;

class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return FormInterface|null
     */
    public function getForm()
    {
        return $this->form;
    }
}
