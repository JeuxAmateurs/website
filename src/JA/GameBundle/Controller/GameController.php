<?php

namespace JA\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        $entities = $em->getRepository('JAGameBundle:Game')->findAll();
		
        return $this->render('JAGameBundle:Game:list.html.twig', array('entities' => $entities));
    }

    /**
     * Finds and displays a Game entity.
     *
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JAGameBundle:Game')->find($id);

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
     *
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
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Game();
        $form = $this->createForm(new GameType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('game_show', array('id' => $entity->getId(), 'slug' => $entity->getSlug())));
        }

        return $this->render('JAGameBundle:Game:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Game entity.
     *
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
     *
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

            return $this->redirect($this->generateUrl('game_edit', array('id' => $id, 'slug' => $slug)));
        }

        return $this->render('JAGameBundle:Game:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Game entity.
     *
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
