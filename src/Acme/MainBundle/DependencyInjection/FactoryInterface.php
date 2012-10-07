<?php

namespace Acme\MainBundle\DependencyInjection;

use Acme\MainBundle\Entity\Track;

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

    public function searchForTrack($query, $page);

    /**
     * Prepare the force download
     *
     * @return void
     */

    public function processDownload(Track $track);

}
