<?php

namespace JA\NewsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Voter\FieldVote;

use JMS\SecurityExtraBundle\Annotation\Secure;

use JA\NewsBundle\Entity\News;
use JA\NewsBundle\Form\NewsType;

/**
 * News controller.
 *
 */
class NewsController extends Controller
{
	/**
     * Displays all News entities in a page.
     *
     */
    public function indexAction()
    {
        return $this->render('JANewsBundle:News:index.html.twig');
    }
	
	/**
     * Displays all News entities.
     *
     */
    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JANewsBundle:News')->findAll();
		
        return $this->render('JANewsBundle:News:list.html.twig', array('entities' => $entities));
    }
	
    /**
     * Finds and displays a News entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JANewsBundle:News')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $deleteForm = $this->createDeleteForm($slug);

        return $this->render('JANewsBundle:News:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new News entity.
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $entity = new News();
        $form   = $this->createForm(new NewsType(), $entity);

        return $this->render('JANewsBundle:News:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new News entity.
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $entity  = new News();
        $form = $this->createForm(new NewsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
			$securityContext = $this->get('security.context');
			$user = $securityContext->getToken()->getUser();
			if(!is_object($user))
			{
				throw new AccessDeniedException('You are not authenticated');
			}
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
			
			// Build the bidirectional relation
			$games = $form['games']->getData();
			foreach($games as $game)
			{
				$entity->addGame($game);
			}
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			
			$aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);
			
			$roleSecurityIdentity = new RoleSecurityIdentity('ROLE_ADMIN');
			$acl->insertObjectAce($roleSecurityIdentity, MaskBuilder::MASK_MASTER);
			
			$acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
			// $acl->insertObjectFieldAce('title', $securityIdentity, MaskBuilder::MASK_VIEW);
            $aclProvider->updateAcl($acl);

            return $this->redirect($this->generateUrl('news_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('JANewsBundle:News:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing News entity.
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JANewsBundle:News')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }
		
		$securityContext = $this->get('security.context');
        if (false === $securityContext->isGranted('EDIT', $entity))
        {
            throw new AccessDeniedException('You don\'t have permissions to do this');
        }
		/* if (false === $securityContext->isGranted('EDIT', new FieldVote($entity, 'id')))
        {
            throw new AccessDeniedException('You don\'t have permissions to do this');
        } */

        $editForm = $this->createForm(new NewsType(), $entity);
        $deleteForm = $this->createDeleteForm($slug);

        return $this->render('JANewsBundle:News:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing News entity.
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JANewsBundle:News')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }
		
		$securityContext = $this->get('security.context');
        if (false === $securityContext->isGranted('EDIT', $entity))
        {
            throw new AccessDeniedException('You don\'t have permissions to do this');
        }

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createForm(new NewsType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('news_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('JANewsBundle:News:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a News entity.
     * @Secure(roles="ROLE_USER")
     */
    public function deleteAction(Request $request, $slug)
    {
        $form = $this->createDeleteForm($slug);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JANewsBundle:News')->findOneBySlug($slug);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find News entity.');
            }
			
			$securityContext = $this->get('security.context');
			if (false === $securityContext->isGranted('DELETE', $entity))
			{
				throw new AccessDeniedException('You don\'t have permissions to do this');
			}

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('news'));
    }

    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder(array('slug' => $slug))
            ->add('slug', 'hidden')
            ->getForm()
        ;
    }
}
