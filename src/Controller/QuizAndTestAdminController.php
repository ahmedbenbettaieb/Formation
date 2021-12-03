<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizAndTestAdminController extends AbstractController
{
    /**
     * @Route("/quiz/and/test/admin", name="quiz_and_test_admin")
     */
    public function index(): Response
    {
        return $this->render('quiz_and_test_admin/index.html.twig', [
            'controller_name' => 'QuizAndTestAdminController',
        ]);
    }
}
