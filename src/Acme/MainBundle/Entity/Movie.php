<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\MainBundle\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acme\MainBundle\Entity\Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity
 */
class Movie
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $body
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;


    /**
     * @var string $photo
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @var string $translation
     *
     * @ORM\Column(name="translation", type="string", length=255)
     */
    private $translation;

    /**
     * @var string $remote_key
     *
     * @ORM\Column(name="remoteKey", type="string", length=255, unique=true)
     */
    private $remoteKey;
    
    /**
     * @var string $remote_key
     *
     * @ORM\Column(name="embed", type="text", nullable=true)
     */
    private $embed;

    /**
     * @var float $ratingcount
     *
     * @ORM\Column(name="ratingCount", type="integer")
     */
    private $ratingCount;

    /**
     * @var float $ratingvalue
     *
     * @ORM\Column(name="ratingValue", type="float")
     */
    private $ratingValue;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Category", inversedBy="movies")
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;
    
    /**
     * Constructor
     */
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
     * Set title
     *
     * @param string $title
     * @return Movie
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
     * Set body
     *
     * @param string $body
     * @return Movie
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Movie
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set translation
     *
     * @param string $translation
     * @return Movie
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
    
        return $this;
    }

    /**
     * Get translation
     *
     * @return string 
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set remoteKey
     *
     * @param string $remoteKey
     * @return Movie
     */
    public function setRemoteKey($remoteKey)
    {
        $this->remoteKey = $remoteKey;
    
        return $this;
    }

    /**
     * Get remoteKey
     *
     * @return string 
     */
    public function getRemoteKey()
    {
        return $this->remoteKey;
    }

    /**
     * Set embed
     *
     * @param string $embed
     * @return Movie
     */
    public function setEmbed($embed)
    {
        $this->embed = $embed;
    
        return $this;
    }

    /**
     * Get embed
     *
     * @return string 
     */
    public function getEmbed()
    {
        return $this->embed;
    }

    /**
     * Set ratingCount
     *
     * @param integer $ratingCount
     * @return Movie
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;
    
        return $this;
    }

    /**
     * Get ratingCount
     *
     * @return integer 
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    /**
     * Set ratingValue
     *
     * @param float $ratingValue
     * @return Movie
     */
    public function setRatingValue($ratingValue)
    {
        $this->ratingValue = $ratingValue;
    
        return $this;
    }

    /**
     * Get ratingValue
     *
     * @return float 
     */
    public function getRatingValue()
    {
        return $this->ratingValue;
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
     * @return Movie
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