<?php

namespace Acme\MainBundle\Extension;

use Doctrine\ORM\EntityManager;
use Twig_Extension, Twig_Filter_Method;

class Twig extends Twig_Extension
{
    protected $entityManager;
    
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
    }
    
    public function getGlobals()
    {
        return array
        (
            'footerContainer' => $this->entityManager->getRepository('AcmeMainBundle:Content')->findAll()
        );
    }
    
    public function getFilters()
    {
        return array
        (
            'cut' => new Twig_Filter_Method($this, 'cut')
        );
    }
    
    public function cut($string, $length = 300)
    {
        if(mb_strlen($string) == mb_strlen(mb_substr($string, 0, $length)))
        {
            return $string;
        }
        
        return mb_substr($string, 0, $length) . '...';
    }
    
    public function getName()
    {
        return 'Twig';
    }
}
