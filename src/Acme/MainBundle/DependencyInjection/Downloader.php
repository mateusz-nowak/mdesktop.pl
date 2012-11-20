<?php

namespace Acme\MainBundle\DependencyInjection;

use Acme\MainBundle\Entity\Track;

class Downloader
{
    /** @var $prefixTitle string */
    protected $prefixTitle;

    public function setPrefixTitle($prefixTitle)
    {
        $this->prefixTitle = $prefixTitle;
    }

    public function process(Track $track, $trackDownloadUrl, $context = null)
    {
        preg_match('/(?P<size>\d\.\d+) Mb/', $track->getSize(), $tmpSize);
        // var_dump(stream_context_get_params($context));die;

        header('Content-type: media/x-mp3');
        header(sprintf('Content-disposition: attachment; filename="(%s) %s.mp3"', $this->prefixTitle, $track->getTitle()));
        header('Content-Length: ' . $tmpSize['size']*1048576);

        readfile($trackDownloadUrl, false, $context);
    }
}
