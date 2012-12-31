<?php

namespace JA\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JAUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
