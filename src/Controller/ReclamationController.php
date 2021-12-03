<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\AddReclamationType;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation/{id}", name="reclamation")
     */
    public function reclamation(User $user): Response
    {
        $reclamations = $this->getDoctrine()
        ->getRepository(Reclamation::class)
        ->findBy(array('idUserRec' => $user->getId()));
        return $this->render('reclamation/reclamation.html.twig',[
            'reclamations' => $reclamations
        ]);
    }

    /**
     * @Route("/reclamation/{id}/add", name="add_reclamation")
     */
    public function addReclamation(User $user, Request $request): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(AddReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatut("En attente");
            $reclamation->setAdminTrait(0);
            $reclamation->setIdUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $rec=$this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findBy(array('idUserRec' => $user->getId()));

            $last=count($rec);

            return $this->redirectToRoute('reclamation', array('id' => $user->getId()));
        }
        return $this->render('reclamation/add_reclamation.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reclamation-print/{id}", name="reclamation_print")
     */
    public function reclamationPrint(Reclamation $rec): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Times New Roman');
    
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
    
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/reclamationPrint.html.twig', [
            'reclamation' => $rec
        ]);
    
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
    
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Réclamation.pdf", [
        "Attachment" => true
        ]);
        
    }
}
