<?php

namespace Acme\MainBundle\Admin;

use Acme\MainBundle\Admin\Admin;
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
        $filter
            ->add('title', null, array('label' => 'content.title'))
            ->add('text', null, array('label' => 'content.text'))
            ->add('categories', null, array('label' => 'content.categories'));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, array('label' => 'content.title'))
            ->add('commentable', null, array('label' => 'content.commentable'))
            ->add('_action', 'actions', array(
                'label' => 'actions',
                'actions' => array(
                    'view' => array(),
                    'delete' => array(),
                )
            ));

        return $listMapper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('content.basic')
                ->add('title', null, array('label' => 'content.title'))
                ->add('text', null, array('label' => 'content.text', 'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'medium',
                    'style' => join(';', array(
                        'width: 800px',
                        'height: 300px',
                    ))
                )))
            ->end()
            ->with('content.optionally')
                ->add('categories', null, array(
                    'label' => 'content.categories',
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository
                            ->createQueryBuilder('c')
                            ->where('c.type = :type')
                            ->setParameter('type', 1);
                    }
                ))
                ->add('commentable', null, array('label' => 'content.commentable'))
                ->add('photos', null, array('label' => 'content.photos', 'required' => false))
            ->end();

        return $formMapper;
    }
}
