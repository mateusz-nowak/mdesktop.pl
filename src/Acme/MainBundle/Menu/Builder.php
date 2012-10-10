<?php

namespace Acme\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\MenuFactory;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        
        $menu->addChild('Strona Główna', array('route' => 'root'));
        $menu->addChild('Muzyka', array('route' => 'track_search'));
        $menu->addChild('Filmy', array('route' => 'movie'));
        
        $menu->setCurrent(true);
        
        return $menu;
    }
}