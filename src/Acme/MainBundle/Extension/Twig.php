<?php

namespace Acme\MainBundle\Extension;

use Acme\MainBundle\Repository\Content;
use Doctrine\ORM\EntityManager;
use Twig_Extension, Twig_Filter_Method, Twig_Function_Method;

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
            'footerContainer' => $this->entityManager->getRepository('AcmeMainBundle:Content')->findAllByCategoryName(Content::PAGE),
        );
    }

    public function getFunctions()
    {
        return array
        (
            'photoTrack' => new Twig_Function_Method($this, 'getPhotoTrack'),
        );
    }

    public function getPhotoTrack($trackData)
    {
        if ($trackData) {
            $images = $trackData->getImages();

            return end($images);
        } else {
            return null;
        }
    }

    public function getFilters()
    {
        return array
        (
            'cut' => new Twig_Filter_Method($this, 'getCuttedVersion')
        );
    }

    public function getCuttedVersion($string, $length = 300)
    {
        if (mb_strlen($string) == mb_strlen(mb_substr($string, 0, $length))) {
            return $string;
        }

        return mb_substr($string, 0, $length) . '...';
    }

    public function getName()
    {
        return 'Twig';
    }
}
