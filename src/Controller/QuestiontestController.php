<?php

namespace App\Controller;

use App\Entity\Questiontest;
use App\Entity\Test;
use App\Form\QuestiontestType;
use App\Repository\QuestiontestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier ;

/**
 * @Route("/questiontest")
 */
class QuestiontestController extends AbstractController
{
    /**
     * @Route("/", name="questiontest_index", methods={"GET"})
     */
    public function index( QuestiontestRepository $questiontestRepository): Response
    {
        return $this->render('questiontest/index.html.twig', [
            'questiontests' => $questiontestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="questiontest_new", methods={"GET","POST"})
     */
    public function new(Request $request,$id, FlashyNotifier $flashy): Response
    {
        $questiontest = new Questiontest();
        $form = $this->createForm(QuestiontestType::class, $questiontest);
        $form->handleRequest($request);
        $test = $this->getDoctrine()->getRepository(Test::class)->find($id) ;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $questiontest->setIdTest($test) ;
            $entityManager->persist($questiontest);
            $entityManager->flush();
            $flashy->success('Question ajoutée avec succès!');
            return $this->redirectToRoute('test_show', ['id' => $questiontest->getIdTest()->getId() ] );
        }

        return $this->render('questiontest/new.html.twig', [
            'questiontest' => $questiontest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questiontest_show", methods={"GET"})
     */
    public function show(Questiontest $questiontest): Response
    {
        return $this->render('questiontest/show.html.twig', [
            'questiontest' => $questiontest,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="questiontest_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Questiontest $questiontest, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(QuestiontestType::class, $questiontest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Question modifiée avec succès!');
            return $this->redirectToRoute('test_show', ['id' => $questiontest->getIdTest()->getId() ] );
        }

        return $this->render('questiontest/edit.html.twig', [
            'questiontest' => $questiontest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questiontest_delete", methods={"POST"})
     */
    public function delete(Request $request, Questiontest $questiontest, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questiontest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($questiontest);
            $entityManager->flush();
        }

        $flashy->success('Question supprimée avec succès!');
        return $this->redirectToRoute('test_show', ['id' => $questiontest->getIdTest()->getId() ] );
    }
}
