<?php

namespace Acme\MainBundle\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Acme\MainBundle\Entity\Content;

/**
 * @ORM\Entity(repositoryClass="Acme\MainBundle\Repository\Content")
 * @ORM\Table(name="photo")
 * @ORM\HasLifecycleCallbacks()
 */
class Photo
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
     * @var string $title
     *
     * @ORM\Column()
     * @Assert\NotNull()
     * @Assert\Image(maxSize="6000000")
     */
    private $file;

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
     * @ORM\ManyToMany(targetEntity="Acme\MainBundle\Entity\Content", mappedBy="photos", cascade={"persist"})
     */
    private $items;

    public function __construct()
    {
        $this->createdAt = new Datetime;
        $this->updatedAt = new Datetime;
    }

    public function getAbsolutePath()
    {
        return null === $this->file ? null : $this->getUploadRootDir() . '/' . $this->file;
    }

    public function getWebPath()
    {
        return null === $this->file ? null : $this->getUploadDir() . '/' . $this->file;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/photo';
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function uploadFile()
    {
        $fileName = sha1($this->file->getClientOriginalName() . $this->getId() . mt_rand(0, 99999)) . '.' . $this->file->guessExtension();

        $this->file->move(
            $this->getUploadRootDir(),
            $fileName
        );

        $this->setFile($fileName);
    }

    public function getfullPath()
    {
        return '/' . $this->getWebPath();
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
     * Set file
     *
     * @param  string $file
     * @return Photo
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Photo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return Photo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function __toString()
    {
        return sprintf("zdjecie#%d", $this->getId());
    }

    /**
     * Set path
     *
     * @param  string $path
     * @return Photo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add items
     *
     * @param  Acme\MainBundle\Entity\Content $items
     * @return Photo
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
