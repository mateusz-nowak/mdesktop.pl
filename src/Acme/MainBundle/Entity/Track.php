<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Acme\MainBundle\Entity\Comment;

/**
 * Acme\MainBundle\Entity\Movie
 *
 * @ORM\Table(name="track")
 * @ORM\Entity(repositoryClass="Acme\MainBundle\Repository\Track")
 * @ORM\HasLifecycleCallbacks()
 */
class Track
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
     * @var string $title
     *
     * @ORM\Column(type="string", unique=true, length=32)
     */
    private $remote;

    /**
     * @var string $title
     *
     * @ORM\Column(name="size", type="string", length=16, nullable=true)
     */
    private $size;

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
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Comment", inversedBy="items", cascade={"persist"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="commentId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="trackId", referencedColumnName="id")}
     * )
     */
    private $comments;

    public function __construct()
    {
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
     * Set title
     *
     * @param  string $title
     * @return Track
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
     * Set remote
     *
     * @param  string $remote
     * @return Track
     */
    public function setRemote($remote)
    {
        $this->remote = $remote;

        return $this;
    }

    /**
     * Get remote
     *
     * @return string
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set size
     *
     * @param  string $size
     * @return Track
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Add comments
     *
     * @param  Acme\MainBundle\Entity\Comment $comments
     * @return Track
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
     * Set createdAt
     *
     * @param  string $createdAt
     * @return Track
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
     * @param  string $updatedAt
     * @return Track
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

    public function getSlug()
    {
        return sprintf("%d-%s", $this->getId(), str_replace(' ', '-', $this->getTitle()));
    }
}