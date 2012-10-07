<?php

namespace Acme\MainBundle\Extension;

use Doctrine\ORM\EntityManager;
use Twig_Extension;

class Twig extends Twig_Extension
{
    protected $entityManager;
    
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
    }
    
    public function getGlobals()
    {
        return array(
            'footerContainer' => $this->entityManager->getRepository('AcmeMainBundle:Content')->findByCategory(1),
        );
    }
    
    public function getName()
    {
        return 'Twig';
    }
}
