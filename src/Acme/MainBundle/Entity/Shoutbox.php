<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Acme\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Acme\MainBundle\Entity\Shoutbox
 *
 * @ORM\Table(name="shoutbox")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Shoutbox
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
     * @var string $text
     *
     * @ORM\Column(name="text", type="text")
     * @Assert\Regex(pattern="#^<p>(\s|&nbsp;|\xA0)*<\/p>$#i", match=false)
     */
    private $text;

    /**
     * @var Acme\UserBundle\Entity\User $text
     *
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User", inversedBy="shouts", cascade={"remove"})
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $user;

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

    public function __construct()
    {
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
     * Set text
     *
     * @param  string   $text
     * @return Shoutbox
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
     * Set user
     *
     * @param  \stdClass $user
     * @return Shoutbox
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->getText();
    }

    /**
     * Set createdAt
     *
     * @param  string   $createdAt
     * @return Shoutbox
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
     * @param  string   $updatedAt
     * @return Shoutbox
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
}
