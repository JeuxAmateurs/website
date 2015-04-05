<?php

namespace JA\AppBundle\Security;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;

class ApiRequestMatcher implements RequestMatcherInterface
{
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function matches(Request $request)
    {
        return preg_match('#^api\.(.+)#', $request->getHost())
        || (in_array($this->kernel->getEnvironment(), array('dev', 'frontdev')) && $request->get('_api'));
    }
}
