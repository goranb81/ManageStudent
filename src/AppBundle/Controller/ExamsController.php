<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\HelpClass\Passedexam;

/**
 * Exams controller.
 *
 * @Route("/exams")
 */
class ExamsController extends Controller
{
    /**
     * Return all results for particularly exam.
     *
     * @Route("/getallresult", name="get_all_result")
     * @Method("POST")
     */
    public function getAllResultExams(Request $request){
        
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        /*
         * return table with students which is updated
         */
        $exam = $em->getRepository('AppBundle:Exams')->find($id);
        $passedexams = $exam->getPassedexams();
        
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
        
        /*
        $engine = $this->container->get('templating');
        $template = $engine->render('users/tableModalResults.html.twig', array(
            'passedexams' => $passedexams,
        ));
        


        $response = array(
            'content' => $template
        );

        return new JsonResponse($response); 
         */
    }

}
