<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passedexams
 *
 * @ORM\Table(name="passedexams", indexes={@ORM\Index(name="student_id", columns={"student_id"}), @ORM\Index(name="exam_id", columns={"exam_id"})})
 * @ORM\Entity
 */
class Passedexams
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepass", type="date", nullable=false)
     */
    private $datepass;

    /**
     * @var integer
     *
     * @ORM\Column(name="mark", type="integer", nullable=false)
     */
    private $mark;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Exams
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Exams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $exam;

    /**
     * @var \AppBundle\Entity\Students
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Students")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    
    
    private $student;



    /**
     * Set dateofbirth
     *
     * @param \DateTime $datepass
     *
     * @return Passedexams
     */
    public function setDatepass($datepass)
    {
        $this->datepass = $datepass;

        return $this;
    }

    /**
     * Get datepass
     *
     * @return \DateTime
     */
    public function getDatepass()
    {
        return $this->datepass;
    }

    /**
     * Set mark
     *
     * @param integer $mark
     *
     * @return Passedexams
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return integer
     */
    public function getMark()
    {
        return $this->mark;
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
     * Set exam
     *
     * @param \AppBundle\Entity\Exams $exam
     *
     * @return Passedexams
     */
    public function setExam(\AppBundle\Entity\Exams $exam = null)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam
     *
     * @return \AppBundle\Entity\Exams
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Students $student
     *
     * @return Passedexams
     */
    public function setStudent(\AppBundle\Entity\Students $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Students
     */
    public function getStudent()
    {
        return $this->student;
    }
}
