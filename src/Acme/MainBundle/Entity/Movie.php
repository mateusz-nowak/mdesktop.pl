<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\MainBundle\Entity\Category;
use Acme\MainBundle\Entity\Comment;

/**
 * Acme\MainBundle\Entity\Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="Acme\MainBundle\Repository\Movie")
 * @ORM\HasLifecycleCallbacks()
 */
class Movie
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
     * @ORM\Column(name="embed", type="string", length=32)
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
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Category", inversedBy="items", cascade={"persist"})
     * @ORM\JoinTable(
     *      name="movie_category",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     **/
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Comment", inversedBy="comments", cascade={"persist"})
     */
    private $comments;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->createdAt = new Datetime;
        $this->updatedAt = new Datetime;
    }

    /**
     * Get full photo url
     *
     * @return string
     */
    public function getFullPhoto()
    {
        return 'http://kinoland.pl/' . $this->photo;
    }

    /**
     * Set photo
     *
     * @param  string $photo
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
     * @param  string $translation
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
     * @param  string $remoteKey
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
     * @param  string $embed
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
     * @param  integer $ratingCount
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
     * @param  float $ratingValue
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
     * @param  string $slug
     * @return Movie
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
     * @param  string $title
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
     * Set text
     *
     * @param  string $text
     * @return Movie
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
     * @param  Acme\MainBundle\Entity\Category $categories
     * @return Movie
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
     * Add comments
     *
     * @param  Acme\MainBundle\Entity\Comment $comments
     * @return Movie
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Acme\MainBundle\Entity\Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add categories
     *
     * @param  Acme\MainBundle\Entity\Category $categories
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