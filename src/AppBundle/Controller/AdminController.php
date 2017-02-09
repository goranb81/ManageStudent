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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/*
 * Admin page
 */
class AdminController extends Controller{
   
    /*
     * Admin page
     */
    
    /**
     * @Route("/admin", name="ahome");
     */
    public function adminAction(){
        
        return $this->render('admin/adminpage.html.twig');
    }
    
    /*
     * action which lists all students in admin page
     */
    
    /**
     * @Route("/admin/student", name="astudent");
     */
    public function studentAction(){
        
        $repository = $this->getDoctrine()->getRepository('AppBundle:Students');
        $students = $repository->findAll();
        return $this->render('admin/students.html.twig', array('students' => $students));
    }
    
    /*
     * action which lists all passedexams in admin page
     */
    
    /**
     * @Route("/admin/passedexams", name="pexams");
     */
    public function passedexamsAction(){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Passedexams');
        $passedexams = $repository->findAll();
        return $this->render('admin/passedexams.html.twig', array('passedexams' => $passedexams));
    }
      
}

