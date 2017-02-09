<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Passedexam
 *
 * @author Goran
 * 
 * This class is helper class. This class is similar like Passedexams entity class.
 * Difference is that Passedexam class don't have object Student and object Exam.
 * It has only student name and exam name. 
 */

namespace AppBundle\HelpClass;

class Passedexam {
     
    private $datepass;

    private $mark;
   
    private $id;
  
    private $examname;
 
    private $studentname;

    
    public function setDatepass($datepass)
    {
        $this->datepass = $datepass;

        return $this;
    }

    public function getDatepass()
    {
        return $this->datepass;
    }

    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    public function getMark()
    {
        return $this->mark;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExamName($examname)
    {
        $this->examname = $examname;

        return $this;
    }
    
    public function getExamName()
    {
        return $this->examname;
    }

    public function setStudentName($studentname)
    {
        $this->studentname = $studentname;

        return $this;
    }

    
    public function getStudentName()
    {
        return $this->studentname;
    }
}
