<?php

namespace Acme\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\MainBundle\Form\Type\CommentType;
use Acme\MainBundle\Entity\Comment;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * Edit Comment
     *
     * @Route("/{id}/comment", name="comment_edit")
     * @Method({"GET"})
     * @Template()
     */
    public function editAction(Comment $comment)
    {
        if (!$this->getUser() || !$this->getUser()->canManageComment($comment)) {
            throw new AccessDeniedException();
        }

        if (preg_match('/[content|track|movie]/', $this->getRequest()->headers->get('referer'))) {
            $this->get('session')->set('referer', $this->getRequest()->headers->get('referer'));
        }

        $form = $this->createForm(new CommentType, $comment);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Update Comment
     *
     * @Method({"POST"})
     * @Route("/{id}/comment")
     * @Template()
     */
    public function updateAction(Comment $comment)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createForm(new CommentType, $comment);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $em->merge($comment);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Komentarz został zedytowany');
            } else {
                $this->get('session')->setFlash('error', 'Nie udało się zedytować komentarza');
                }
        }

        return $this->redirect($this->get('session')->get('referer'));
    }

    /**
     * Destroy Comment
     *
     * @Route("/{id}/delete", name="comment_delete")
     * @Template()
     */
    public function deleteAction(Comment $comment)
    {
        if (!$this->getUser() || !$this->getUser()->canManageComment($comment)) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.entity_manager');

        $em->remove($comment);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}
