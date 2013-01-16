<?php

namespace Acme\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Acme\MainBundle\Entity\Comment;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Acme\MainBundle\Entity\Comment", mappedBy="user", cascade={"all"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="Acme\MainBundle\Entity\Shoutbox", mappedBy="user", cascade={"all"})
     */
    private $shouts;

    /**
     * @ORM\OneToMany(targetEntity="Acme\MainBundle\Entity\Playlist", mappedBy="user", cascade={"all"})
     */
     private $tracks;

    public function __construct()
    {
        parent::__construct();
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

    public function getTracks()
    {
        return $this->tracks;
    }

    public function canManageComment(Comment $comment)
    {
        return (bool) $comment->getUser() == $this;
    }

}
