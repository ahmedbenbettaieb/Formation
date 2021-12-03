<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quiz;
use App\Entity\Test;
use App\Entity\Questionquiz ;
use App\Entity\Questiontest ;
use App\Repository\QuizRepository;
use App\Repository\TestRepository;
use App\Entity\Note ;
use App\Entity\User ;


/**
 * @Route("/quiz_front")
 */
class QuizAndTestFrontController extends AbstractController
{
    /**
     * @Route("/", name="quiz_and_test_front")
     */
    public function index(Request $request): Response
    {
        $search = "" ;
        $quizzes = $this->getDoctrine()->getRepository(Quiz::class)->findAll() ;
        $tests = $this->getDoctrine()->getRepository(Test::class)->findAll() ;

        if($request->request->get('search'))
        {
            $search = trim($request->request->get('search')," ");
            $tests = $this->getDoctrine()->getRepository(Test::class)->rechercherTestFront($search);
            $quizzes = $this->getDoctrine()->getRepository(Quiz::class)->rechercherQuizFront($search) ;
        }

        return $this->render('quiz_and_test_front/index.html.twig', [
            'listQuiz' => $quizzes ,
            'listTest' => $tests ,
            'search' => $search
        ]);
    }

    /**
     * @Route("/takeQuiz/{id}", name="take_quiz", methods={"GET","POST"})
     */
    public function takeQuiz($id) : Response
    {
        $q= $this->getDoctrine()->getRepository(Quiz::class)->find($id) ;
        return $this->render('quiz_and_test_front/take_quiz.html.twig', [
            'quiz' => $q ,
        ]);

    }

    /**
     * @Route("/takeTest/{id}", name="take_test", methods={"GET","POST"})
     */
    public function takeTest(Request $request, $id) : Response
    {
        $t= $this->getDoctrine()->getRepository(Test::class)->find($id) ;
        $iduser = $request->request->get('user') ;
        $note = $this->getDoctrine()->getRepository(Note::class)
            ->findBy(array('idEtudiant'=> $iduser,'idTest'=>$id)) ;
        if($note){
            foreach($note as $n)
            {
                if((($n->getNoteObtenue()/$t->getTotalPoint())*100) >= 70)
                {
                    return $this->redirectToRoute('certificat', ['id' => $id] );

                }
                else{
                    return $this->render('quiz_and_test_front/take_test.html.twig', [
                        'test' => $t ,
                    ]);
                }
            }

        }
        else{
            return $this->render('quiz_and_test_front/take_test.html.twig', [
                'test' => $t ,
            ]);
        }
    }

    /**
     * @Route("/validateQuiz/{id}", name="validate_quiz", methods={"GET","POST"})
     */
    public function  validateQuiz(Request $request,$id)
    {
        $note = 0 ;
        $pourcentage = 0 ;
        $q = new Quiz();
        $q= $this->getDoctrine()->getRepository(Quiz::class)->find($id) ;
        $responses = new ArrayCollection() ;
        foreach($q->getQuestions() as $question){
            $questPosee = $question->getDesignation();
            $param = str_replace(" ", "_", $questPosee, $count);
            $responses->add([$questPosee =>$request->request->get($param)]) ;
        }

        foreach($responses as $rep){
            $questRep = array_keys($rep)[0] ;
            $valRep = array_values($rep)[0] ;
            foreach($q->getQuestions() as $question)
            {
                if( (strcmp($questRep, $question->getDesignation()) === 0) && (strcmp($valRep, $question->getReponseCorrecte()) === 0) )
                {
                    $note = ($note + $question->getNote()) ;
                }
            }

        }
        $totalPoints = $q->getTotalPoint() ;
        $pourcentage = round(($note / $totalPoints) *100);

        return $this->render('quiz_and_test_front/quiz_result.html.twig', [
            'quiz' => $q ,
            'note' => $note ,
            'percent' => $pourcentage ,
            'totalpoints' => $totalPoints,
        ]);
    }

    /**
     * @Route("/answersQuiz/{id}", name="answers_quiz", methods={"GET","POST"})
     */
    public function ShowQuizAnswers($id) : Response
    {
        $q= $this->getDoctrine()->getRepository(Quiz::class)->find($id) ;
        return $this->render('quiz_and_test_front/show_answers_quiz.html.twig', [
            'quiz' => $q ,
        ]);

    }

    /**
     * @Route("/validateTest/{id}", name="validate_test", methods={"GET","POST"})
     */
    public function  validateTest(Request $request,$id)
    {
        $test = new Test() ;
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id) ;
        $iduser = $request->request->get('user') ;
        $user = $this->getDoctrine()->getRepository(User::class)->find($iduser) ;

        $note = 0 ;
        $pourcentage = 0 ;
        $responses = new ArrayCollection() ;
        foreach($test->getQuestions() as $question){
            $questPosee = $question->getDesignation();
            $param = str_replace(" ", "_", $questPosee, $count);
            $responses->add([$questPosee =>$request->request->get($param)]) ;
        }

        foreach($responses as $rep){
            $questRep = array_keys($rep)[0] ;
            $valRep = array_values($rep)[0] ;
            foreach($test->getQuestions() as $question)
            {
                if( (strcmp($questRep, $question->getDesignation()) === 0) && (strcmp($valRep, $question->getReponseCorrecte()) === 0) )
                {
                    $note = ($note + $question->getNote()) ;
                }
            }

        }
        $totalPoints = $test->getTotalPoint() ;
        $pourcentage = round(($note / $totalPoints) *100);

        $n = $this->getDoctrine()->getRepository(Note::class)
            ->findBy(array('idEtudiant'=> $iduser,'idTest'=>$id)) ;
        if(!$n){
            $n = new Note() ;
            $n->setIdTest($test) ;
            $n->setIdEtudiant($user) ;
            $n->setNoteObtenue($note) ;

            $nbPasses = ($test->getNbEtudiantPasses() + 1);
            $test->setNbEtudiantPasses($nbPasses) ;

            if($pourcentage >= 70){
                $nbAdmis = ($test->getNbEtudiantsAdmis() + 1) ;
                $test->setNbEtudiantsAdmis($nbAdmis) ;
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($n);
            $entityManager->flush();


        }
        else{
            foreach($n as $nt){
                $nt->setNoteObtenue($note) ;
                if($pourcentage >= 70){
                    $nbAdmis = ($test->getNbEtudiantsAdmis() + 1);
                    $test->setNbEtudiantsAdmis($nbAdmis);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
            }
        }

        return $this->render('quiz_and_test_front/test_result.html.twig',
                               [    'test' => $test,
                                    'note' => $note ,
                                    'percent' => $pourcentage ,
                                    'totalpoints' => $totalPoints,
                                ]);
    }

    /**
     * @Route("/certificat/{id}", name="certificat", methods={"GET","POST"})
     */
    public function showCertificate($id):Response
    {
        $t =  $this->getDoctrine()->getRepository(Test::class)->find($id) ;
        return $this->render('quiz_and_test_front/show_certificat.html.twig', [
            'test' => $t ,
        ]);
    }

    /**
     * @Route("/download/{id}", name="download", methods={"GET","POST"})
     */
    public function downloadCertificate($id, Pdf $knpSnappy):Response
    {
        $t =  $this->getDoctrine()->getRepository(Test::class)->find($id) ;
        $this->knpSnappy = $knpSnappy;
        $html = $this->renderView('quiz_and_test_front/download.html.twig', [
            'test' => $t
        ]);
        $filename = 'certificat';

        return new Response(
            $this->knpSnappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
       /*return $this->render('quiz_and_test_front/download.html.twig', [
            'test' => $t ,
        ]);*/
    }
}