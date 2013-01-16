<?php

namespace Acme\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
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
            $track = $em->getRepository('AcmeMainBundle:Track')->findOneByRemote($trackObjectValue['remote']);

            if (!$track) {
                $track = new TrackEntity;

                $track->setTitle($trackObjectValue['title']);
                $track->setRemote($trackObjectValue['remote']);

                $em->persist($track);
                $em->flush();
            }

            $remoteId[] = $trackObjectValue['remote'];
        }

        return $this->findByRemote($remoteId);
    }
}
