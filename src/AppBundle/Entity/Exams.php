<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exams
 *
 * @ORM\Table(name="exams")
 * @ORM\Entity
 */
class Exams implements \JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Passedexams", mappedBy="exam")
     */
    private $passedexams;
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Exams
     */
    public function setName($name)
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
        return $this->name;
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
     * Constructor
     */
    public function __construct()
    {
        $this->passedexams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add passedexam
     *
     * @param \AppBundle\Entity\Passedexams $passedexam
     *
     * @return Exams
     */
    public function addPassedexam(\AppBundle\Entity\Passedexams $passedexam)
    {
        $this->passedexams[] = $passedexam;

        return $this;
    }

    /**
     * Remove passedexam
     *
     * @param \AppBundle\Entity\Passedexams $passedexam
     */
    public function removePassedexam(\AppBundle\Entity\Passedexams $passedexam)
    {
        $this->passedexams->removeElement($passedexam);
    }

    /**
     * Get passedexams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassedexams()
    {
        return $this->passedexams;
    }

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }

}
