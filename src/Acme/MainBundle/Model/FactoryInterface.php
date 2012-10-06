<?php

namespace Acme\MainBundle\Model;

interface FactoryInterface
{

    /**
     * Get the informations about one track
     *
     * @return array
     */

    public function getTrackInfo($trackRemoteKey);

    /**
     * Fetch all tracks for query
     *
     * @return ArrayIterator
     */

    public function	searchForTrack($query, $page);

}
