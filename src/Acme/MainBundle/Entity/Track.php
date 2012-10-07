<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="track")
 * @ORM\HasLifecycleCallbacks()
 */

class Track
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
     * @ORM\Column(unique=false)
     */
    private $title;

    /**
     * @var string $length
     *
     * @ORM\Column(nullable=true)
     */
    private $size;

    /**
     * @var string $url
     *
     * @ORM\Column(type="integer")
     */
    private $remote_id;

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
     * @return Track
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
     * Set key
     *
     * @param  string $key
     * @return Track
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set remote_id
     *
     * @param  integer $remoteId
     * @return Track
     */
    public function setRemoteId($remoteId)
    {
        $this->remote_id = $remoteId;

        return $this;
    }

    /**
     * Get remote_id
     *
     * @return integer
     */
    public function getRemoteId()
    {
        return $this->remote_id;
    }
}
