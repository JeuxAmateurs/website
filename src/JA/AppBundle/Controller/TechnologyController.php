<?php

namespace JA\AppBundle\Controller;

use JA\AppBundle\Entity\Technology;
use JA\AppBundle\Form\Type\TechnologyFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormTypeInterface;

use JA\AppBundle\Exception\InvalidFormException;

/**
 * This class must NOT implement ClassResourceInterface,
 * the pluralization is made with the names in its methods
 */
class TechnologyController extends FOSRestController
{
    /**
     * Get all technologies.
     * Empty list if there's no technologies
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets all technologies",
     *   output = "JA\AppBundle\Entity\Technology",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAAppBundle:Technology:cget.html.twig",
     *      templateVar="technologies"
     * )
     *
     * @return array
     *
     */
    public function cgetTechnologiesAction()
    {
        $technologies = $this->getTechnologyHandler()->getAll();

        return $technologies;
    }

    /**
     * Get single technology.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Technology for a given id",
     *   output = "JA\AppBundle\Entity\Technology",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the technology is not found"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAAppBundle:Technology:get.html.twig",
     *      templateVar="technology"
     * )
     *
     * @param string $slug   the technology slug
     *
     * @return array
     *
     * @throws NotFoundHttpException when technology not exist
     */
    public function getTechnologyAction($slug)
    {
        if(!($technology = $this->getTechnologyHandler()->get($slug))) {
            throw $this->createNotFoundException("The resource '". $slug ."' was not found.");
        }

        return $technology;
    }

    /**
     * Presents the form to use to create a new Technology.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAAppBundle:Technology:new.html.twig",
     * )
     *
     * @return FormTypeInterface
     */
    public function newTechnologyAction()
    {
        if(false === $this->get('security.authorization_checker')->isGranted('create', 'JA\AppBundle\Entity\Technology'))
        {
            throw $this->createAccessDeniedException();
        }

        return $this->createForm(new TechnologyFormType(), null, array('action' => $this->generateUrl('api_1_get_technologies')));
    }

    /**
     * Create a new technology from submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Create a new Technology from data sent",
     *   output = "",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     204 = "Data already exists",
     *     400 = "The data sent is not valid",
     *     422 = "The technology data sent contains errors"
     *   }
     * )
     *
     * If the template is returned, you have a bad request
     * @Rest\View(
     *      template="JAAppBundle:Technology:new.html.twig",
     *      statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request
     *
     * @return FormTypeInterface|View
     */
    public function postTechnologyAction(Request $request)
    {
        if(false === $this->get('security.authorization_checker')->isGranted('create', 'JA\AppBundle\Entity\Technology'))
        {
            $this->get('logger')->debug('{user} can\'t create technology.', array('user' => $this->get('security.token_storage')->getToken()->getUser()->getUsername()));
            throw $this->createAccessDeniedException();
        }

        try
        {
            // Technology handler create a new Technology.
            $newTechnology = $this->getTechnologyHandler()->post(
                $request->request->get(TechnologyFormType::NAME)
            );

            $routeOptions = array(
                'slug' => $newTechnology->getSlug()
            );

            $view = $this->routeRedirectView('api_1_get_technology', $routeOptions, Codes::HTTP_CREATED);
            $view->setData($newTechnology); // we send the data to avoid multiple requests

            return $view;
        }
        catch(InvalidFormException $exception)
        {
            return $exception->getForm();
        }
    }

    /**
     * Presents the form to use to edit a Technology.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "When the Technology was not found"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAAppBundle:Technology:edit.html.twig",
     * )
     *
     * @param string $slug The technology slug to edit
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException
     */
    public function editTechnologyAction($slug)
    {
        if(!$technology = $this->getTechnologyHandler()->get($slug))
            throw $this->createNotFoundException('The resource ' . $slug . ' was not found.');

        if(false === $this->get('security.authorization_checker')->isGranted('edit', $technology))
            throw $this->createAccessDeniedException();

        $form = $this->createForm(
            new TechnologyFormType(),
            $technology,
            array(
                'action' => $this->generateUrl(
                    'api_1_put_technology',
                    array('slug' => $technology->getSlug())
                ),
                'method' => 'put'
            )
        );

        return $form;
    }

    /**
     * Edit or create a technology from submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit or create a new Technology from data sent",
     *   output = "",
     *   statusCodes = {
     *     201 = "Returned when the data doesn't exist already",
     *     204 = "Returned when successful",
     *     400 = "The data sent is not valid",
     *     422 = "The technology data sent contains errors"
     *   }
     * )
     *
     * If the template is returned, you have a bad request
     * @Rest\View(
     *      template="JAAppBundle:Technology:edit.html.twig",
     *      statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request
     * @param string $slug The slug to identify the technology
     *
     * @return FormTypeInterface|View
     */
    public function putTechnologyAction(Request $request, $slug)
    {
        try
        {
            // if data doesn't exist, we create it
            $formName = $request->request->get(TechnologyFormType::NAME);
            if(!$technology = $this->getTechnologyHandler()->get($slug))
            {
                $code = Codes::HTTP_CREATED;
                $technology = $this->getTechnologyHandler()->post(
                    $formName
                );
            }
            else
            {
                if(false === $this->get('security.authorization_checker')->isGranted('edit', $technology))
                    throw $this->createAccessDeniedException();

                $code = Codes::HTTP_NO_CONTENT;
                $technology = $this->getTechnologyHandler()->put(
                    $technology,
                    $formName
                );
            }

            $routeOptions = array(
                'slug' => $technology->getSlug()
            );

            $view = $this->routeRedirectView('api_1_get_technology', $routeOptions, $code);
            if($code === Codes::HTTP_CREATED)
                $view->setData($technology); // we send the data to avoid multiple requests

            return $view;
        }
        catch(InvalidFormException $exception)
        {
            return $exception->getForm();
        }
    }

    /**
     * Get a form to delete a technology.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Get a form to delete a technology",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "The data sent is not valid"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAAppBundle:Technology:remove.html.twig",
     *      templateVar="form"
     * )
     *
     * @param string $slug The slug to identify the technology
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException
     */
    public function removeTechnologyAction($slug)
    {
        if(!$technology = $this->getTechnologyHandler()->get($slug))
            $this->createNotFoundException();

        if(false === $this->get('security.authorization_checker')->isGranted('delete', $technology))
            throw $this->createAccessDeniedException();

        $deleteForm = $this->createDeleteForm($slug);

        return $deleteForm;
    }

    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->add('Delete', 'submit')
            ->setAction($this->generateUrl('api_1_delete_technology', array('slug' => $slug)))
            ->setMethod('delete')
            ->getForm()
            ;
    }

    /**
     * Delete a technology.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Technology",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful"
     *   }
     * )
     *
     * @todo: See for the redirection after success
     * @Rest\View(
     *      template="JAAppBundle:Technology:remove.html.twig",
     * )
     *
     * @param string $slug The slug to identify the technology
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException
     */
    public function deleteTechnologyAction($slug)
    {
        if($technology = $this->getTechnologyHandler()->get($slug))
        {
            if(false === $this->get('security.authorization_checker')->isGranted('delete', $technology))
            {
                $this->get('logger')->debug('User can\'t delete this technology');
                throw $this->createAccessDeniedException();
            }

            $this->getTechnologyHandler()->delete($technology);
        }

        $view = $this->routeRedirectView('api_1_get_technologies', array(), Codes::HTTP_NO_CONTENT);

        return $view;
    }

    private function getTechnologyHandler()
    {
        return $this->container->get('ja_app.technology.handler');
    }
}
