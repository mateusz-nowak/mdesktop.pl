<?php

namespace Acme\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ContentAdmin extends Admin
{
    
    /** @var $em \Doctrine\ORM\EntityManager */
    protected $em;
    
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function configureShowFields(ShowMapper $filter)
    {
        $filter->add('title', null, array('label' => 'sonata.page.title'))
               ->add('text', null, array('label' => 'sonata.page.text'))
               ->add('categories', null, array('label' => 'sonata.page.categories'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title', null, array('label' => 'sonata.page.title'))
                   ->add('_action', 'actions', array(
                       'actions' => array(
                           'view' => array(),
                           'delete' => array(),
                       )
                   ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', null, array('label' => 'sonata.page.title'))
                   ->add('text', null, array('label' => 'sonata.page.text'))
                   ->add('categories', null, array(
                        'label' => 'sonata.page.categories',
                        'query_builder' => function (EntityRepository $repository) 
                        {
                            return $repository
                                ->createQueryBuilder('c')
                                ->where('c.type = :type')
                                ->setParameter('type', 1);
                        }
                   ));

        return $formMapper;
    }
}
