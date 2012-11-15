<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\MainBundle\Entity\Category;
use Acme\MainBundle\Entity\Photo;
use Acme\MainBundle\Entity\Comment;

/**
 * @ORM\Entity(repositoryClass="Acme\MainBundle\Repository\Content")
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
     * @var boolean $commentable
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $commentable = true;

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
     * @Assert\Regex(pattern="#^<p>(\s|&nbsp;|\xA0)*<\/p>$#i", match=false)
     */
    private $text;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Category", inversedBy="items", cascade={"persist"})
     * @ORM\JoinTable(
     *      name="content_category",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     **/
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Comment", inversedBy="items", cascade={"persist"})
     */
    private $comments;

    /**
     * @var \Datetime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \Datetime $updatedAt
     *
     * @Gedmo\Timestampable
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Photo", inversedBy="items", cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="photo_id", referencedColumnName="id")}
     * )
     **/
    private $photos;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->createdAt = new Datetime;
        $this->updatedAt = new Datetime;
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
     * @param  string  $slug
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
     * @param  string  $title
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
     * @param  string  $text
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
     * @param  Acme\MainBundle\Entity\Category $categories
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
     * Set createdAt
     *
     * @param  string  $createdAt
     * @return Content
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  string  $updatedAt
     * @return Content
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add comments
     *
     * @param  Acme\MainBundle\Entity\Comment $comments
     * @return Content
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
     * Set commentable
     *
     * @param  boolean $commentable
     * @return Content
     */
    public function setCommentable($commentable)
    {
        $this->commentable = $commentable;

        return $this;
    }

    /**
     * Get commentable
     *
     * @return boolean
     */
    public function getCommentable()
    {
        return $this->commentable;
    }

    /**
     * Set photos
     *
     * @param  Acme\MainBundle\Entity\Photo $photos
     * @return Content
     */
    public function setPhotos(Photo $photos = null)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get photos
     *
     * @return Acme\MainBundle\Entity\File
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add photos
     *
     * @param  Acme\MainBundle\Entity\Photo $photos
     * @return Content
     */
    public function addPhoto(Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param Acme\MainBundle\Entity\Photo $photos
     */
    public function removePhoto(Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Add categories
     *
     * @param  Acme\MainBundle\Entity\Category $categories
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
	
	public function getThumbnail()
	{
		if($this->photos->first()) {
			return $this->photos->first()->getFullPath();	
		}
	}
}