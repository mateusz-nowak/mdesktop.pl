<?php

namespace Acme\MainBundle\Extension;

use Acme\MainBundle\ValueObject\Meta;
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
    	$meta = Meta::getInstance();
		
        return array(
            'footerContainer' => $this->entityManager->getRepository('AcmeMainBundle:Content')->findAllByCategoryName(Content::PAGE),
            'lastTitle' => $meta->getLastTitle(),
        );
    }

    public function getFunctions()
    {
        return array(
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
        return array(
            'cut' => new Twig_Filter_Method($this, 'getCuttedVersion')
        );
    }

    public function getCuttedVersion($string, $length = 300)
    {
    	$string = strip_tags($string);
		
        if (mb_strlen($string, 'UTF-8') == mb_strlen(mb_substr($string, 0, $length, 'UTF-8'), 'UTF-8')) {
            return $string;
        }

        return mb_substr($string, 0, $length, 'UTF-8') . '...';
    }

    public function getName()
    {
        return 'Twig';
    }
}
