<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\RegistrationFormateurType;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRole("Etudiant");
            $user->setstatus("Approuvé");
            $user->setLogin($form->get('email')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register-formateur", name="app_register_formateur")
     */
    public function registerFormateur(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['CurriculumVitae']->getData();
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
                $user->setCurriculumVitae($newFilename);
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRole("Formateur");
            $user->setstatus("En attente");
            $user->setLogin($form->get('email')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/registerFormateur.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/wait-approval", name="wait_approval")
     */
    public function waitApproval(): Response
    {
        return $this->render('registration/wait_approval.html.twig');
    }
}
