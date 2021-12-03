<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetStartedController extends AbstractController
{
    /**
     * @Route("/", name="get_started")
     */
    public function getStarted(): Response
    {
        return $this->render('get_started/getStarted.html.twig');
    }
}
