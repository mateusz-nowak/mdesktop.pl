<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Acme\MainBundle\Entity\Content;
use Acme\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @var text $text
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     * @Assert\Regex(pattern="#^<p>(\s|&nbsp;|\xA0)*<\/p>$#i", match=false)
     */
    private $text;

    /**
     * @var Acme\UserBundle\Entity\User $text
     *
     * @ORM\ManyToOne(targetEntity="Acme\UserBundle\Entity\User", inversedBy="comments")
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

    /**
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Content", mappedBy="comments", cascade={"persist"})
     */
    private $items;

    public function __construct()
    {
        $this->createdAt = new Datetime;
        $this->updatedAt = new Datetime;
    }

    /**
     * Set text
     *
     * @param  string  $text
     * @return Comment
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set objectEntity
     *
     * @param  string  $objectEntity
     * @return Comment
     */
    public function setObjectEntity($objectEntity)
    {
        $this->objectEntity = $objectEntity;

        return $this;
    }

    /**
     * Get objectEntity
     *
     * @return string
     */
    public function getObjectEntity()
    {
        return $this->objectEntity;
    }

    /**
     * Set createdAt
     *
     * @param  string  $createdAt
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  string  $updatedAt
     * @return Comment
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
     * Set user
     *
     * @param  Acme\UserBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(User $user = null)
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

    public function __toString()
    {
        return sprintf("%s#%d", $this->getUser(), $this->getId());
    }

    /**
     * Add items
     *
     * @param  Acme\MainBundle\Entity\Content $items
     * @return Comment
     */
    public function addItem(Content $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param Acme\MainBundle\Entity\Content $items
     */
    public function removeItem(Content $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}
