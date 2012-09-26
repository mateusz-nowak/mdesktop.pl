<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks()
 */

class Category
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $slug
     *
     * @ORM\Column()
     */
    private $slug;

    /**
     * @var string $title
     *
     * @ORM\Column()
     * @Assert\NotNull()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="content", mappedBy="category")
     */

    private $collections;

    public function __construct()
    {
        $this->collections = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */

    public function addSlug()
    {
        $this->setSlug(mb_strtolower(str_replace(' ', '-', $this->getName())));
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param  string   $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set name
     *
     * @param  string   $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add collections
     *
     * @param  Acme\MainBundle\Entity\content $collections
     * @return Category
     */
    public function addCollection(\Acme\MainBundle\Entity\content $collections)
    {
        $this->collections[] = $collections;

        return $this;
    }

    /**
     * Remove collections
     *
     * @param Acme\MainBundle\Entity\content $collections
     */
    public function removeCollection(\Acme\MainBundle\Entity\content $collections)
    {
        $this->collections->removeElement($collections);
    }

    /**
     * Get collections
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCollections()
    {
        return $this->collections;
    }
}
