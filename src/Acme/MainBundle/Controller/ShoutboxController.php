<?php

namespace Acme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Acme\MainBundle\Form\Type\ShoutboxType;
use Acme\MainBundle\Entity\Shoutbox;

/**
 * Content controller.
 *
 * @Route("/shoutbox")
 */
class ShoutboxController extends Controller
{
    /**
     * Display shoutbox.
     *
     * @Route("", name="shoutbox")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new ShoutboxType);

        return array(
            'form' => $form->createView(),
            'entries' => $this->getDoctrine()->getRepository('AcmeMainBundle:Shoutbox')->getLastShouts(20),
        );
    }

    /**
     * Display shoutbox.
     *
     * @Route("/new", name="shoutbox_new")
     * @Method({"POST"})
     * @Template()
     */
    public function newAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $shoutbox = new Shoutbox;
        $form = $this->createForm(new ShoutboxType, $shoutbox);

        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());

            if ($form->isValid()) {

                $shoutbox->setUser($this->getUser());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($shoutbox);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Dziękujemy za twój wpis!');
            } else {
                $this->get('session')->setFlash('error', 'Wystąpił błąd podczas dodawania wpisu');
            }
        }

        return $this->redirect($this->generateUrl('root'));
    }
}
