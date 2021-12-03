<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReclamationsApiController extends AbstractController
{
    /**
     * @Route("/reclamations_api", name="reclamations_api")
     */
    public function getReclamations(SerializerInterface $serializer): Response
    {
        $donnees = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findAll();
        $json = $serializer->serialize($donnees,'json',['groups'=>'reclamation']);
        return new JsonResponse($json, 200);
    }

    /**
     * @Route("/reclamations_api_by_user", name="reclamations_api_by_user")
     */
    public function getReclamationsByUser(SerializerInterface $serializer, Request $request): Response
    {
        $content=$request->getContent();
        $idSent=substr($content,8,-3);
        $donnees = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findBy(array('idUserRec' => $idSent));
        $json = $serializer->serialize($donnees,'json',['groups'=>'reclamation']);
        return new JsonResponse($json, 200);
    }

    /**
     * @Route("/reclamations_api_add", name="reclamations_api_add")
     */
    public function addReclamation(SerializerInterface $serializer, Request $request, EntityManagerInterface $em): Response
    {
        $content=$request->getContent();
        $ch=substr($content,12);
        $pos=strpos($ch,',');
        $idU=substr($ch,0,$pos);
        
        $data=$serializer->deserialize($content,Reclamation::class,'json');

        if($data instanceof \App\Entity\Reclamation){
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(array('id' => (int)$idU));
            
                foreach($users as $user){
                    $data->setIdUser($user);
                }
            if($user == null){
                return new JsonResponse('Not Success : ', 200);
            }
            else{
                $em->persist($data);
                $em->flush();
                return new JsonResponse('Success', 200);
            }
            
        }
        else{
            return new JsonResponse('Not Success : ', 200);
        }
    }

    /**
     * @Route("/reclamations_api_repondre", name="reclamations_api_repondre")
     */
    public function repondreReclamation(SerializerInterface $serializer, Request $request, EntityManagerInterface $em, \Swift_Mailer $mailer): Response
    {
        $content=$request->getContent();
        //Récuperer la reclamation et l'utilisateur qui l'a envoyé
        $ch=substr($content,11);
        $idRec=substr($ch,0,strpos($ch,","));
        //Récuperer l'id de l'admin traitant
        $chh=substr($ch,strpos($ch,",")+13);
        $idAdmin=substr($chh,0,strpos($chh,","));
        //Récuperer le contenu de reponse
        $chhh=substr($chh,strpos($chh,",")+14);
        $reponse=substr($chhh,0,strpos($chhh,"\""));

        $reclamations=$this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findBy(array('id' => $idRec));
        foreach($reclamations as $reclamation){
            $user=$reclamation->getIdUserRec();
        }

        $reclamation->setAdminTrait((int) $idAdmin);
        //$em->persist($reclamation);
        //$em->flush();

        $message = (new \Swift_Message('Réponse Réclamation'))
                        ->setFrom('esenpai.devnation@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody($reponse);
            
        $mailer->send($message);

        return new JsonResponse('Success', 200);
    }

}
