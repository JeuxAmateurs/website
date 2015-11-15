<?php
/**
 * User: kilian
 * Date: 11/15/15
 * Time: 3:08 PM
 */

namespace JA\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();


        // Customize your response object to display the exception details
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $message = sprintf(
                "{\r\n\tcode : %s,\r\n\tmessage : '%s'\r\n}",
                $exception->getStatusCode(),
                $exception->getMessage()
            );
            $response->setContent($message);
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $message = sprintf(
                "{\r\n\tcode : %s,\r\n\tmessage : '%s'\r\n}",
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
            $response->setContent($message);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}