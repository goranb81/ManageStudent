<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Students
 *
 * @ORM\Table(name="students")
 * @ORM\Entity
 */
class Students implements \JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateofbirth", type="date", nullable=false)
     */
    private $dateofbirth;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\Passedexams", mappedBy="student")
     */
    private $passedexams;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Students
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
     * Set dateofbirth
     *
     * @param \DateTime $dateofbirth
     *
     * @return Students
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;

        return $this;
    }

    /**
     * Get dateofbirth
     *
     * @return \DateTime
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
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
     * @return Students
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
