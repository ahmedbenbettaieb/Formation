<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersApiController extends AbstractController
{
    /**
     * @Route("/users_api", name="users_api")
     */
    public function getUsers(NormalizerInterface $normalizer): Response
    {
        $donnees = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        $json = $normalizer->normalize($donnees,'json',['groups'=>'user']);
        return new JsonResponse($json, 200);
    }

    /**
     * @Route("/users_api_by_email", name="users_api_by_id")
     */
    public function getUserById(NormalizerInterface $normalizer, Request $request): Response
    {
        $emailSent=$request->query->get('email');
        $donnees = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array('email' => $emailSent));
        foreach($donnees as $user){
            $id = $user->getId();
        }
        return new JsonResponse($id, 200);
    }

    
    /**
     * @Route("/users_api_add", name="users_api_add")
     */
    public function addUser(Request $request, NormalizerInterface $normalizer, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em=$this->getDoctrine()->getManager();
        $user = new User();
        $user->setNom($request->query->get('nom'));
        $user->setPrenom($request->query->get('prenom'));
        $user->setDateNaissance($request->query->get('date_naissance'));
        $user->setSexe($request->query->get('sexe'));
        $user->setEmail($request->query->get('email'));
        $user->setRole($request->query->get('role'));
        $user->setLogin($request->query->get('email'));
        $user->setPassword($passwordEncoder->encodePassword(
            $user,
            $request->query->get('password')
        ));
        $user->setstatus($request->query->get('status'));
        $user->setPhotoProfil($request->query->get('photoProfil'));
        $user->setBiography($request->query->get('biography'));
        $user->setCurriculumVitae($request->query->get('curriculumVitae'));

        $em->persist($user);
        $em->flush();

        $json = $normalizer->normalize($user,'json',['groups'=>'user']);
        return new JsonResponse($json);
    }

    /**
     * @Route("/user_auth", name="user_auth")
     */
    public function authUser(Request $request, NormalizerInterface $normalizer, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $emailSent=$request->query->get('email');
        $passwordSent=$request->query->get('password');

        $exist=false;
        $passTrue=false;
        $id=0;
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(array('email' => $emailSent));
        
        foreach($users as $user){
            $myemail = $user->getEmail();
            $mypassword = $user->getPassword();
            $exist=true;
            $passTrue=$passwordEncoder->isPasswordValid($user, $passwordSent);
            $id = $user->getId();
        }
        
        if($exist==true && $passTrue==true)
        {
            $json = $normalizer->normalize($user,'json',['groups'=>'user']);
            return new JsonResponse($json, 200);
        }
        else{
            $ok="not ok";
            return new JsonResponse($ok, 200);
        }
    }

    /**
     * @Route("/users_api_edit", name="users_api_edit")
     */
    public function editUser(Request $request, NormalizerInterface $normalizer, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $conn=$em->getConnection();
        $sql='UPDATE User SET nom=:nom, prenom=:prenom, date_naissance=:date_naissance, sexe=:sexe, email=:email,login=:login, password=:password, photo_profil=:photo_profil, biography=:biography, curriculum_vitae=:cv WHERE id=:id';
        $stmt = $conn->prepare($sql);
        $pass=$passwordEncoder->encodePassword(
            $user,
            $request->query->get('password')
        );
        $stmt->execute(['nom' => $request->query->get('nom'), 'prenom' => $request->query->get('prenom'),'date_naissance' => $request->query->get('dateNaissance'),'sexe' => $request->query->get('sexe'),'email' => $request->query->get('email'),'login' => $request->query->get('email'),'password' => $pass,'photo_profil' => $request->query->get('photoProfil'),'biography' => $request->query->get('biography'),'cv' => $request->query->get('curriculumVitae'),'id' => $request->query->get('id')]);
        $donnees = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->query->get('id'));
        $json = $normalizer->normalize($donnees,'json',['groups'=>'user']);
        return new JsonResponse($json, 200);
        
    }
}
