<?php

namespace JA\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Voter\FieldVote;

use JMS\SecurityExtraBundle\Annotation\Secure;

use JA\GameBundle\Entity\Game;
use JA\GameBundle\Form\GameType;

/**
 * Game controller.
 *
 */
class GameController extends Controller
{
    /**
     * Displays all Game entities in a page.
     *
     */
    public function indexAction()
    {
        return $this->render('JAGameBundle:Game:index.html.twig');
    }
	
	/**
     * Displays all Game entities.
     *
     */
    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JAGameBundle:Game')->findAllWithAllDependencies();
		
        return $this->render('JAGameBundle:Game:list.html.twig', array('entities' => $entities));
    }

    /**
     * Finds and displays a Game entity.
     *
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:Game')->findOneWithAllDependencies($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JAGameBundle:Game:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Game entity.
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $entity = new Game();
        $form   = $this->createForm(new GameType(), $entity);

        return $this->render('JAGameBundle:Game:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Game entity.
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $entity  = new Game();
        $form = $this->createForm(new GameType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
			// Get the current user
			$securityContext = $this->get('security.context');
			$user = $securityContext->getToken()->getUser();
			if(!is_object($user))
			{
				throw new AccessDeniedException('You are not authenticated');
			}
            $securityIdentity = UserSecurityIdentity::fromAccount($user);
			
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

            return $this->redirect($this->generateUrl('game_show', array('id' => $entity->getId(), 'slug' => $entity->getSlug())));
        }

        return $this->render('JAGameBundle:Game:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Game entity.
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $editForm = $this->createForm(new GameType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JAGameBundle:Game:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Game entity.
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request, $id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:Game')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Game entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GameType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('game_show', array('id' => $id, 'slug' => $entity->getSlug())));
        }

        return $this->render('JAGameBundle:Game:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Game entity.
     * @Secure(roles="ROLE_USER")
     */
    public function deleteAction(Request $request, $id, $slug)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JAGameBundle:Game')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Game entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('game'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
