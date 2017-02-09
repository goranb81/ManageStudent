<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Students;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\HelpClass\Passedexam;

/**
 * Students controller.
 *
 * @Route("/students")
 */
class StudentsController extends Controller {

    /**
     * Lists all Students entities.
     *
     * @Route("/", name="students_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository('AppBundle:Students')->findAll();

        $template = $this->renderView('admin/students.html.twig', array(
            'students' => $students,
        ));

        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new Students entity.
     *
     * @Route("/new", name="students_new")
     * @Method("POST")
     */
    public function newAction(Request $request) {

        $name = $request->request->get('name');
        $date = $request->request->get('date');
        $student = new Students();
        $pdate = new \DateTime(\date("Y-m-d", \strtotime($date)));

        $student->setName($name);
        $student->setDateofbirth($pdate);

        $em = $this->getDoctrine()->getManager();
        $em->persist($student);
        $em->flush();


        $response = array(
            'id' => $student->getId()
        );

        return new JsonResponse($response);
        //return $this->createTableTemplate();      
    }

    /**
     * Displays a form to edit an existing Students entity.
     *
     * @Route("/edit", name="students_edit")
     * @Method("POST")
     */
    public function editAction(Request $request) {
        /*
         * get parameters from ajax
         */
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $date = $request->request->get('date');

        /*
         * find student for edit
         */
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Students')->find($id);

        /*
         * edit student and persists
         */
        $pdate = new \DateTime(\date("Y-m-d", \strtotime($date)));

        $student->setName($name);
        $student->setDateofbirth($pdate);
        $em->flush();

        $response = array(
            'content' => 'OK'
        );

        return new JsonResponse($response);
    }

    /**
     * Deletes a Students entity.
     *
     * @Route("/delete", name="students_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request) {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Students')->find($id);

        $em->remove($student);
        $em->flush();

        $response = array('content' => 'OK');

        return new JsonResponse($response);
    }

    /**
     * Return modal Form.
     *
     * @Route("/getform", name="get_form")
     * @Method("POST")
     */
    public function formAction(Request $request) {

        $id = $request->request->get('id_edit');
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository('AppBundle:Students')->find($id);

        $engine = $this->container->get('templating');
        $template = $engine->render('admin/modalForm.html.twig', array(
            'student' => $student,
        ));

        $response = array(
            'content' => $template
        );

        return new JsonResponse($response);
    }

    private function createTableTemplate() {
        $em = $this->getDoctrine()->getManager();
        /*
         * return table with students which is updated
         */
        $students = $em->getRepository('AppBundle:Students')->findAll();
        $engine = $this->container->get('templating');
        $template = $engine->render('admin/table_students.html.twig', array(
            'students' => $students,
        ));



        $response = array(
            'content' => $template
        );

        return new JsonResponse($response);
    }

    /**
     * Return all passed exams for particularly student.
     *
     * @Route("/getallexams", name="get_all_exams")
     * @Method("POST")
     */
    public function getAllPassedExams(Request $request) {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        /*
         * return table with students which is updated
         */
        $student = $em->getRepository('AppBundle:Students')->find($id);
        //$passedexams = array();
        $passedexams = $student->getPassedexams();
        
        //convert Collection into Array
        $passedexams_array = $passedexams->getValues(); 
        
        /* Convert Collection into Array. 
           new helper class Passedexam's array
         */
        $help_array = array();
        
        //create help array from $passedexams_array
        $length = count($passedexams_array);
        for($i=0; $i<$length; $i++) {
            $passedexam = $passedexams_array[$i];
            $help_passedexam = new Passedexam();
            $help_passedexam->setDatepass($passedexam->getDatepass());
            $help_passedexam->setMark($passedexam->getMark());
            $help_passedexam->setExamName($passedexam->getExam()->getName());
            $help_passedexam->setStudentName($passedexam->getStudent()->getName());
            $help_array[] = $help_passedexam;
        }

        $passedexams_json = $this->get('serializer')->serialize($help_array, 'json');

        //return new JsonResponse($passedexams_json);
        return new Response($passedexams_json);
    }

}
