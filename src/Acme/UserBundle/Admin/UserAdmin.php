<?php

namespace Acme\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin
{
    public function configureShowFields(ShowMapper $filter)
    {
        $filter
            ->add('username', null, array('label' => 'user.username'))
            ->add('email', null, array('label' => 'user.email'))
            ->add('enabled', null, array('label' => 'user.enabled'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', null, array('label' => 'user.username'))
            ->add('email', null, array('label' => 'user.email'))
            ->add('enabled', null, array('label' => 'user.enabled'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'delete' => array(),
                )
            ));

        return $listMapper;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('create');
    }
}
