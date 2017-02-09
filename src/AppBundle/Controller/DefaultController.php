<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Students;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\HelpClass\Passedexam;

class DefaultController extends Controller {

    /**
     * @Route("/test", name="test")
     */
    public function indexAction(Request $request) {
        //Fetch all students from DB
        /*
          $em = $this->getDoctrine()->getManager();
          $students = $em->getRepository('AppBundle:Students')->findAll();
          //$passedexams = $students->getPassedexams();
          $students1 = \json_encode($students);
         */

        $em = $this->getDoctrine()->getManager();

        // Get all passedexams belongs student with id = 12
        $student = $em->getRepository('AppBundle:Students')->find(12);
        $passedexams = $student->getPassedexams();

        //convert Collection into Array
        $passedexams_array = $passedexams->getValues();

        //new helper class Passedexam's array
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

        //print $passedexams_array content
        //$passedexams_string = \var_dump($passedexams_array);
        //serialize $passedexams_array into JSON
        $passedexams_json = $this->get('serializer')->serialize($help_array, 'json');
        //print $passedexams_json content
        //$passedexams_string = \var_dump($help_array);

        /*
          return $this->render('default/index.html.twig', [
          'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
          'students' => $students1,
          ]);
         * */

        return $this->render('users/TEST.html.twig', [

                    //'PassedExamsArray' => $passedexams_string,
                    'PassedExamsArray' => $passedexams_json,
        ]);
    }

}
