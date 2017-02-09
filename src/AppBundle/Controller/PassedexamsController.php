<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Passedexams;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Passedexams controller.
 *
 * @Route("/pexams")
 */
class PassedexamsController extends Controller
{
    /**
     * Return modal Form.
     *
     * @Route("/getform", name="get_form_exam")
     * @Method("POST")
     */
    public function formAction(Request $request) {

        $id = $request->request->get('id_edit_exam');
        $em = $this->getDoctrine()->getManager();
        $passedexam = $em->getRepository('AppBundle:Passedexams')->find($id);

        $engine = $this->container->get('templating');
        $template = $engine->render('admin/modalFormExams.html.twig', array(
            'passedexam' => $passedexam,
        ));

        $response = array(
            'content' => $template
        );

        return new JsonResponse($response);
    }
    
    /**
     * Return Add form
     *
     * @Route("/all", name="all")
     * @Method("POST")
     */
    public function all(){
       $em = $this->getDoctrine()->getManager();
       $exams = $em->getRepository('AppBundle:Exams')->findAll();
       $students = $em->getRepository('AppBundle:Students')->findAll();
       
       /*
       $engine = $this->container->get('templating');
       $template = $engine->render('admin/modalFormAddExams.html.twig', array(
            'exams' => $exams,
            'students' => $students,
        ));
       
       $response = array(
            'content' => $template
        );
        */
       
       $response = array(
           'exams' => $exams,
           'students' => $students
       );

       return new JsonResponse($response);
    }
    
    private function createTableTemplate(){
        $em = $this->getDoctrine()->getManager();
        /*
         * return table with passedexams which is updated
         */
        $passedexams = $em->getRepository('AppBundle:Passedexams')->findAll();
        $engine = $this->container->get('templating');
        $template = $engine->render('admin/table_passedexams.html.twig', array(
            'passedexams' => $passedexams,
        ));

        $response = array(
            'content' => $template
        );

        return new JsonResponse($response);
    }
   
    /**
     * Creates a new Passedexams entity.
     *
     * @Route("/passednew", name="passedexam_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        /*
         * get data from ajax
         */
        $studentid = $request->request->get('studentid');
        $examid = $request->request->get('examid');
        //$studentname = $request->request->get('studentname');
        //$examname = $request->request->get('examname');
        $date = $request->request->get('date');
        $mark = $request->request->get('mark');
        
        /*
         * set new passedexam
         */
        
        $em = $this->getDoctrine()->getManager();
        $passedexam = new Passedexams();
        $pdate = new \DateTime(\date("Y-m-d", \strtotime($date)));
        $passedexam->setDatepass($pdate);
        $passedexam->setMark($mark);
        
        $student = $em->getRepository('AppBundle:Students')->find($studentid);
        $exam = $em->getRepository('AppBundle:Exams')->find($examid);
        
        $passedexam->setExam($exam);
        $passedexam->setStudent($student);
        
        $em->persist($passedexam);
        $em->flush();
        
        //get id of new passedexam and send into response
        $id = $passedexam ->getId();
        
        $response = array(
            'id' => $id
        );
        
        return new JsonResponse($response);
        
        //return $this->createTableTemplate();  
    }

    /**
     * Edit Passedexam entity.
     * @Route("/edit", name="pass_edit")
     * @Method("POST")
     */
    public function editAction(Request $request)
    {
        /*
         * get parameters from ajax
         */
        $id = $request->request->get('id');
        $mark = $request->request->get('mark');
        $date = $request->request->get('date');

        /*
         * find passedexam for edit
         */
        $em = $this->getDoctrine()->getManager();
        $passedexam = $em->getRepository('AppBundle:Passedexams')->find($id);

        /*
         * edit passedexam and persists
         */
        $pdate = new \DateTime(\date("Y-m-d", \strtotime($date)));

        $passedexam->setMark($mark);
        $passedexam->setDatepass($pdate);
        $em->flush();
        
        $response = array('message' => 'OK');
        return new JsonResponse($response);
        //return $this->createTableTemplate();
    }

    /**
     * Deletes a Passedexams entity.
     *
     * @Route("/delete", name="passedexams_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $passedexam = $em->getRepository('AppBundle:Passedexams')->find($id);

        $em->remove($passedexam);
        $em->flush();

        //return $this->createTableTemplate();
        
        $response = array('message' => 'OK');
        return new JsonResponse($response);
    }
    
}