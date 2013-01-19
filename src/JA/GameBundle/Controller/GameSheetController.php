<?php

namespace JA\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JA\GameBundle\Entity\GameSheet;
use JA\GameBundle\Form\GameSheetType;

/**
 * GameSheet controller.
 *
 */
class GameSheetController extends Controller
{
    /**
     * Lists all GameSheet entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JAGameBundle:GameSheet')->findAll();

        return $this->render('JAGameBundle:GameSheet:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a GameSheet entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:GameSheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSheet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JAGameBundle:GameSheet:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new GameSheet entity.
     *
     */
    public function newAction()
    {
        $entity = new GameSheet();
        $form   = $this->createForm(new GameSheetType(), $entity);

        return $this->render('JAGameBundle:GameSheet:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new GameSheet entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new GameSheet();
        $form = $this->createForm(new GameSheetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gamesheet_show', array('id' => $entity->getId())));
        }

        return $this->render('JAGameBundle:GameSheet:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing GameSheet entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:GameSheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSheet entity.');
        }

        $editForm = $this->createForm(new GameSheetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JAGameBundle:GameSheet:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing GameSheet entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:GameSheet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GameSheet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GameSheetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gamesheet_edit', array('id' => $id)));
        }

        return $this->render('JAGameBundle:GameSheet:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a GameSheet entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JAGameBundle:GameSheet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GameSheet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gamesheet'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
	/**
     * Displays all GameSheet entities.
     *
     */
    public function listAction()
    {
		$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JAGameBundle:GameSheet')->findAll();
		
        return $this->render('JAGameBundle:GameSheet:list.html.twig', array('entities' => $entities));
    }
}
