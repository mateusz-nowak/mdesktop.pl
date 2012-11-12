<?php

namespace Acme\MainBundle\Repository;

use Exception;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Acme\MainBundle\Entity\Track as TrackEntity;

class Track extends EntityRepository
{
    public function batchInsertTracks(array $trackContainer)
    {
    	/** @var $em \Doctrine\ORM\EntityRepository */
    	$em = $this->_em;
		
        $insertBatch = array();
        $remoteId = array();

        if (!$trackContainer) {
            return array();
        }

        foreach ($trackContainer as $trackObjectValue) {
			try {
				$track = new TrackEntity;
			
				$track->setRemote($trackObjectValue['remote']);
				$track->setTitle($trackObjectValue['title']);
			
				$em->persist($track);
	        	$em->flush();
	        } catch (Exception $e) {
	            // ...
	        }
			
			$remoteId[] = $trackObjectValue['remote'];
        }
		
        
        return $this->findByRemote($remoteId);
    }
}
