<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $users = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1 ),
            4
        );

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $requestUser, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($requestUser);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['photoProfil']->getData();
            if($uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/../img';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
                #file name
                $newFilename = $originalFilename.'.'.$uploadedFile->guessExtension();
                $e=$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename

                );
                $user->setPhotoProfil($newFilename);
            }
            
            $user->setRole("Admin");
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setstatus("Approuvé");
            $user->setLogin($form->get('email')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['photoProfil']->getData();
            if($uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/../img';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
                #file name
                $newFilename = $originalFilename.'.'.$uploadedFile->guessExtension();
                $e=$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename

                );
                $user->setPhotoProfil($newFilename);
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


}
