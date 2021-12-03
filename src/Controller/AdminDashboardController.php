<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboard(): Response
    {
        return $this->render('admin_dashboard/adminDashboard.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }
}
