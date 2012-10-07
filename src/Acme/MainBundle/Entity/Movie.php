<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string $translation
     *
     * @ORM\Column(name="translation", type="string", length=255)
     */
    private $translation;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string $remote_key
     *
     * @ORM\Column(name="remote_key", type="string", length=255)
     */
    private $remote_key;

    /**
     * @var float $ratingcount
     *
     * @ORM\Column(name="ratingcount", type="integer")
     */
    private $ratingcount;

    /**
     * @var float $ratingvalue
     *
     * @ORM\Column(name="ratingvalue", type="float")
     */
    private $ratingvalue;

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
     * @param  string $body
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
     * Set category
     *
     * @param  string $category
     * @return Movie
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set remote_key
     *
     * @param  string $remoteKey
     * @return Movie
     */
    public function setRemoteKey($remoteKey)
    {
        $this->remote_key = $remoteKey;

        return $this;
    }

    /**
     * Get remote_key
     *
     * @return string
     */
    public function getRemoteKey()
    {
        return $this->remote_key;
    }

    /**
     * Set ratingcount
     *
     * @param  float $ratingcount
     * @return Movie
     */
    public function setRatingcount($ratingcount)
    {
        $this->ratingcount = $ratingcount;

        return $this;
    }

    /**
     * Get ratingcount
     *
     * @return float
     */
    public function getRatingcount()
    {
        return $this->ratingcount;
    }

    /**
     * Set ratingvalue
     *
     * @param  float $ratingvalue
     * @return Movie
     */
    public function setRatingvalue($ratingvalue)
    {
        $this->ratingvalue = $ratingvalue;

        return $this;
    }

    /**
     * Get ratingvalue
     *
     * @return float
     */
    public function getRatingvalue()
    {
        return $this->ratingvalue;
    }
}
