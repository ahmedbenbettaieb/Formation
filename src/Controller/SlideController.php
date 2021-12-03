<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Slide;
use App\Form\SlideType;
use App\Repository\SlideRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Service\UploaderHelper;
/**
 * @Route("/slide")
 */
class SlideController extends AbstractController
{
    /**
     * @Route("/", name="slide_index", methods={"GET"})
     */
    public function index(SlideRepository $slideRepository): Response
    {
        return $this->render('slide/index.html.twig', [
            'slides' => $slideRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="slide_new", methods={"GET","POST"})
     */
    public function new(Request $request,Formation $id, EntityManagerInterface $entityManager): Response
    {
        $slide = new Slide();
        /** @var UploadedFile $uploadedFile */
        $form1 = $this->createForm(SlideType::class, $slide);
        $form1->handleRequest($request);
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        if ($form1->isSubmitted()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form1['imageFile']->getData();

            if( $uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/public/img/trainers';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
                #file name
                $newFilename = 'img/trainers'.$originalFilename.'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename

                );
                $slide->setImageSlide($newFilename);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $slide->setVideoSlide("");
            $slide->setIdFormation($formation);
            $slide->setTextSlide("");
            $slide->setOrdre(0);

            $entityManager->persist($slide);
            $entityManager->flush();

            return $this->redirectToRoute('formation_create',['id' => $formation->getId()]);
        }

        return $this->render('slide/new.html.twig', [
            'slide' => $slide,
            'formation'=>$formation,
            'form1' => $form1->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slide_show", methods={"GET"})
     */
    public function show(Slide $slide): Response
    {
        return $this->render('slide/show.html.twig', [
            'slide' => $slide,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="slide_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Slide $slide): Response
    {
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slide_index');
        }

        return $this->render('slide/edit.html.twig', [
            'slide' => $slide,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slide_delete", methods={"POST"})
     */
    public function delete(Request $request, Slide $slide): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $id_formation = $slide->getIdFormation();
        $formation = $entityManager->getRepository(Formation::class)->find( $id_formation);

        if ($this->isCsrfTokenValid('delete'.$slide->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($slide);
            $entityManager->flush();
        }
        return $this->redirectToRoute('formation_edit',['id' => $formation->getId()]);

    }

}
