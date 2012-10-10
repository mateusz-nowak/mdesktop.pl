<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\MainBundle\Entity\Category;

/**
 * @ORM\Entity
 * @ORM\Table(name="content")
 * @ORM\HasLifecycleCallbacks()
 */
class Content
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
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string $title
     *
     * @ORM\Column()
     * @Assert\NotNull()
     */
    private $title;

    /**
     * @var text $text
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $text;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Category", inversedBy="children")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;
    
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * @param string $slug
     * @return Content
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
     * Set title
     *
     * @param string $title
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Content
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add categories
     *
     * @param Acme\MainBundle\Entity\Category $categories
     * @return Content
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Acme\MainBundle\Entity\Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    /**
     * Return the title
     * 
     * @return string
     */
    
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Add categories
     *
     * @param Acme\MainBundle\Entity\Category $categories
     * @return Content
     */
    public function addCategorie(\Acme\MainBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Acme\MainBundle\Entity\Category $categories
     */
    public function removeCategorie(\Acme\MainBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }
}