<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DemandesFormateursController extends AbstractController
{
    /**
     * @Route("/demandes/formateurs", name="demandes_formateurs")
     */
    public function demandes(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array('status' => 'En attente'));
        return $this->render('demandes_formateurs/demandes.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/demandes/formateurs/{id}", name="approuver_formateur")
     */
    public function approuver(User $user): Response
    {
        $user->setstatus("Approuvé");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array('status' => 'En attente'));
        
        return $this->render('demandes_formateurs/demandes.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/demandes/formateurs/{id}/CurriculumVitae", name="cv_formateur")
     */
    public function consulterCV(User $user): Response
    {
        return $this->render('demandes_formateurs/showcv.html.twig',[
            'user' => $user
        ]);
    }
}
