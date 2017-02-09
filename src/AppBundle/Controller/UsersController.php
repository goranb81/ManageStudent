<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;

/**
 * Users controller.
 *
 * @Route("/users")
 */
class UsersController extends Controller {
   
    /*
     * users page 
     */
    
    /**
     * @Route("/", name="uhome");
     */
    public function adminAction(){
        
        return $this->render('users/userpage.html.twig');
    }
    
    /*
     * action which show all students in user's page
     */
    
    /**
     * @Route("/students", name="students")
     */
    public function studentsAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Students');
        $students = $repository->findAll();
        return $this->render('users/students.html.twig', array('students' => $students));
    }
    
    /*
     * action which show all exams in user's page
     */
    
    /**
     * @Route("/exams", name="exams")
     */
    public function examsAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Exams');
        $exams = $repository->findAll();
        return $this->render('users/exams.html.twig', array('exams' => $exams));
    }

}
