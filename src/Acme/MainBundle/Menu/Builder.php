<?php

namespace Acme\MainBundle\Menu;

use Acme\MainBundle\ValueObject\Meta;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /** @var $navBuilder array */
    protected $navBuilder = array();

    public function addLocation($title, array $options = array())
    {
        $this->navBuilder[$title] = $options;

        // Meta
        $meta = Meta::getInstance();
        $meta->addTitle($title);

        return $this;
    }

    public function getLocations()
    {
        return $this->navBuilder;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $menu->addChild('Wiadomości', array('route' => 'root'));
        $menu->addChild('Muzyka', array('route' => 'track'));
        $menu->addChild('Filmy', array('route' => 'category'));

        $menu->setCurrent(true);

        return $menu;
    }

    public function navigation(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        /** @var $navigation Acme\MainBundle\Menu\Builder */
        $navigation = $this->container->get('menu_builder_service');

        foreach ($navigation->getLocations() as $key => $value) {
            if ($menu->getChildren()) {
                $menu->addChild(' » ' . $key, $value);
            } else {
                $menu->addChild($key, $value);
            }
        }

        $menu->setCurrent(true);

        return $menu;
    }
}
