<?php

namespace Acme\MainBundle\Repository;

use Exception;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class Track extends EntityRepository
{
    public function batchInsertTracks(array $trackContainer)
    {
        $insertBatch = array();
        $remoteId = array();

        if (!$trackContainer) {
            return array();
        }

        foreach ($trackContainer as $trackObjectValue) {
            $remoteId[] = $trackObjectValue['remote'];
            $insertBatch[] = sprintf('("%s", "%s", now(), now())', addslashes($trackObjectValue['title']), addslashes($trackObjectValue['remote']));
        }

        $plainSql = 'INSERT INTO track (title, remote, createdAt, updatedAt) VALUES ' . join(', ' . PHP_EOL , $insertBatch);
		
        try {
            $this->_em->createNativeQuery($plainSql, new ResultSetMapping)->execute();
        } catch (Exception $e) {
            // ...
        }
		
        return $this->findByRemote($remoteId);
    }
}
