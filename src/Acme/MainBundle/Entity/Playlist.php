<?php

namespace Acme\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Acme\MainBundle\Entity\Movie
 *
 * @ORM\Table(name="playlist")
 * @ORM\Entity
 * @UniqueEntity(fields={"track", "user"})
 * @ORM\HasLifecycleCallbacks()
 */
class Playlist
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
     * @ORM\ManyToOne(targetEntity="Acme\MainBundle\Entity\Track", inversedBy="tracks")
     * @ORM\JoinColumn(name="track_id", referencedColumnName="id")
     */
    private $track;

    /**
     * @var Acme\UserBundle\Entity\User $text
     *
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User", inversedBy="playlist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name = null;

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
     * Set name
     *
     * @param  string   $name
     * @return Playlist
     */
    public function setName($name = null)
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
        if (!$this->name) {
            return 'Nieprzypisane';
        }

        return $this->name;
    }

    public function getDefaultName()
    {
        return $this->name;
    }

    /**
     * Set track
     *
     * @param  Acme\MainBundle\Entity\Track $track
     * @return Playlist
     */
    public function setTrack(\Acme\MainBundle\Entity\Track $track = null)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return Acme\MainBundle\Entity\Track
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set user
     *
     * @param  Acme\UserBundle\Entity\User $user
     * @return Playlist
     */
    public function setUser(\Acme\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Acme\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
