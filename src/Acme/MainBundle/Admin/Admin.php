<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;

class Admin extends BaseAdmin
{
    public function setBaseRouteName($routeName)
    {
        $this->baseRouteName = $routeName;
    }
}
