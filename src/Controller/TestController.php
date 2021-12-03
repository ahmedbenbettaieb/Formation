<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Test;
use App\Entity\User;
use App\Entity\Questiontest ;
use App\Form\TestType;
use App\Repository\TestRepository;
use App\Repository\UserRepository;
use App\Repository\QuestiontestRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier ;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/{id}", name="test_index", methods={"GET","POST"})
     */
    public function index(Request $request,TestRepository $testRepository,$id): Response
    {
        $search="" ;
        $test = $testRepository->findBy(array('idFormateur'=> $id));
        if($request->request->get('search'))
        {
            $search = trim($request->request->get('search')," ");
            $test = $testRepository->rechercherTest($search,$id) ;
        }
        return $this->render('test/index.html.twig', [
            'tests' => $test ,
            'search' => $search
        ]);
    }

    /**
     * @Route("/new/{id}", name="test_new", methods={"GET","POST"})
     */
    public function new(Request $request,$id, FlashyNotifier $flashy): Response
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id) ;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $test->setIdFormateur($user) ;
            $test->setNbEtudiantPasses(0);
            $test->setNbEtudiantsAdmis(0);
            $entityManager->persist($test);
            $entityManager->flush();
            $flashy->success('Test crée avec succès!');
            return $this->redirectToRoute('test_index',['id' => $test->getIdFormateur()->getId() ] );
        }

        return $this->render('test/new.html.twig', [
            'test' => $test,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="test_show", methods={"GET","POST"})
     */
    public function show(Request $request, Test $test): Response
    {
        $questions = $this->getDoctrine()->getRepository(QuestionTest::class)
            ->findBy(array('idTest' => $test->getId()));
        $search = "";
        if ($request->request->get('search')) {
            $search = trim($request->request->get('search')," ");
            $idt = $test->getId() ;
            $questions = $this->getDoctrine()->getRepository(Questiontest::class)
                ->searchQuestion($search,$idt) ;
        }

        return $this->render('test/show.html.twig', [
            'test' => $test,
            'questiontests' => $questions,
            'search' => $search
        ]);
    }

    /**
     * @Route("/{id}/edit", name="test_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Test $test, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Test modifié avec succès!');
            return $this->redirectToRoute('test_index',['id' => $test->getIdFormateur()->getId() ] );
        }

        return $this->render('test/edit.html.twig', [
            'test' => $test,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="test_delete", methods={"POST"})
     */
    public function delete(Request $request, Test $test, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$test->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($test);
            $entityManager->flush();
        }
        $flashy->success('Test supprimé avec succès!');
        return $this->redirectToRoute('test_index',['id' => $test->getIdFormateur()->getId() ] );
    }

    /**
     * @Route("/notes/{id}", name="test_note", methods={"GET"})
     */
    public function showNote($id, ChartBuilderInterface $chartBuilder): Response
    {
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id) ;
        $note = $this->getDoctrine()->getRepository(Note::class)
            ->findBy(array('idTest'=>$id)) ;
        $labels = [];
        $datasets = [];
        foreach($note as $n){
            $labels[] = $n->getIdEtudiant()->getNom() .' '.$n->getIdEtudiant()->getPrenom() ;
            $datasets[] = ($n->getNoteObtenue()/ $test->getTotalPoint())*100 ;
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Stat Notes',
                    'backgroundColor' => '#D75404',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $datasets,

                ]
            ],
        ]);

        return $this->render('test/notes.html.twig', [
            'test' => $test,
            'notes' => $note,
            'chart' => $chart
        ]);
    }
}
