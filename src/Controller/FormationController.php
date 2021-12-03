<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Formation;
use App\Entity\Slide;
use App\Entity\User;
use App\Form\FormationType;
use App\Form\SlideType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/indexmesform/{id}", name="formation_mesformindex", methods={"GET","POST"})
     */
    public function mesFormation( $id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Formation::class);
        $formations = $repo->findBy(array('idFormateur' =>$id));

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,

        ]);
    }


    /**
     * @Route("/rate", name="rate", methods={"GET","POST"})
     */
    public function rate( Request $request ): Response
    {$entityManager = $this->getDoctrine()->getManager();

        if ($request->get('save')) {
            $for = $this->getDoctrine()->getRepository(Formation::class)->find($request->get('fid'));
            $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('uID'));
            $ab = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy(array('idFormation'=> $for , 'idEtudiant'=>$user));
            $repo = $this->getDoctrine()->getRepository(Slide::class);
            $slide = $repo->findBy(array('idFormation' =>$for));
            $ab->setRated(1);
            $for->setNote($request->get('ratedIndex')+1);
            $entityManager->persist($ab);
            $entityManager->flush();


            return $this->render('formation/showSingleFormEtudiant.html.twig', [
                'formation' => $for,
                'slides'=>$slide,
                'user'=>$user,
                'abonnement' => $ab
            ]);
        }


    }

    
    /**
     * @Route("/indexform", name="formation_index", methods={"GET","POST"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $forma = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();
        $formations= $paginator->paginate(
            $forma,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 1)
        );
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        if($request ->isMethod("POST")){
            $titre = $request->get("titre");
            if(!empty($titre)) {
                $formations = $entityManager->getRepository(Formation::class)->findBy(array('titre' => $titre));
            }else{
                $formations = $this->getDoctrine()
                    ->getRepository(Formation::class)
                    ->findAll();
            }
        }
        return $this->render('formation/showforms.html.twig', [
            'formations' => $formations,
            'users'=>$user,
        ]);
    }


    /**
     * @Route("/indexmesformEtudiant/{idUser}", name="formation_indexEtudiant", methods={"GET","POST"})
     */
    public function indexEtudiant(Request $request, $idUser, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnements = $this->getDoctrine()->getRepository(Abonnement::class)->findBy(array('idEtudiant' => $idUser));
        $forma = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        $formations= $paginator->paginate(
            $forma,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 1)
        );
        if($request ->isMethod("POST")){
            $titre = $request->get("titre");
            if(!empty($titre)) {
                $formations = $entityManager->getRepository(Formation::class)->findBy(array('titre' => $titre));
            }else{
                $formations = $this->getDoctrine()
                    ->getRepository(Formation::class)
                    ->findAll();
            }
        }
        return $this->render('formation/showFormsEtudiant.html.twig', [
            'formations' => $formations,
            'abonnements' => $abonnements,
            'users'=>$user,
        ]);
    }

    /**
     * @Route("/accueilFormAdmin", name="accueil_Form_Admin", methods={"GET","POST"})
     */
    public function accueilFormationAdmin(): Response
    {
        $formation = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->findAll();
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('formation/adminFormation.html.twig', [
            'formations' => $formation,
            'users'   => $users,
        ]);
    }






    /**
     * @Route("/accueilForm", name="accueil_Form", methods={"GET","POST"})
     */
    public function accueilFormation(): Response
    {


        return $this->render('formation/accueilformation.html.twig', [

        ]);
    }


    /**
     * @Route("/accueilFormEtudiant", name="accueil_Form_Etudiant",  methods={"GET","POST"})
     */
    public function accueilFormationEtudiant(): Response
    {

        return $this->render('formation/accueilFormationEtudiant.html.twig', [

        ]);
    }
    /**
     * @Route("/new/{id}", name="formation_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $formation->setNote(0);
            $formation->setIdFormateur($user);
            $formation->setDescription("");
            $formation->setTitre("");
            $entityManager->persist($formation);
            $entityManager->flush();
        return $this->redirectToRoute('formation_create', ['id' => $formation->getId(), 'idUser' => $id]);

    }

    /**
     * @Route("/showSingleForm/{id}", name="formation_showsingleform", methods={"GET","POST"})
     */
    public function show(Formation $formation ): Response
    {
       $idUser = $formation->getIdFormateur();
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $repo = $this->getDoctrine()->getRepository(Slide::class);
        $slide = $repo->findBy(array('idFormation' =>$formation));
        return $this->render('formation/showsingleform.html.twig', [
            'formation' => $formation,
            'slides'=>$slide,
            'user'=>$user,
        ]);
    }
    /**
     * @Route("/showSingleFormAdmin/{id}", name="formation_showsingleformAdmin", methods={"GET","POST"})
     */
    public function showFormAdmin(Formation $formation ): Response
    {
        $idUser = $formation->getIdFormateur();
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $repo = $this->getDoctrine()->getRepository(Slide::class);
        $slide = $repo->findBy(array('idFormation' =>$formation));
        return $this->render('formation/showFormAdmin.html.twig', [
            'formation' => $formation,
            'slides'=>$slide,
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/showSingleFormEtudiant/{id}/{idUser}", name="formation_showsingleformEtudiant", methods={"GET"})
     */
    public function showEtudiant(Formation $formation , $idUser ): Response
    {
        $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy(array('idFormation' => $formation,'idEtudiant' => $idUser));
        $idUser = $formation->getIdFormateur();
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $repo = $this->getDoctrine()->getRepository(Slide::class);
        $slide = $repo->findBy(array('idFormation' =>$formation));
        return $this->render('formation/showSingleFormEtudiant.html.twig', [
            'formation' => $formation,
            'slides'=>$slide,
            'user'=>$user,
            'abonnement' => $abonnement
        ]);
    }

    /**
     * @Route("/AbonneForm/{idForm}/{idUser}", name="formation_abonne", methods={"GET","POST"})
     */
    public function abonneForm($idForm, $idUser , Request $request,  FlashyNotifier $flashy): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($idForm);
        $abonnement = new Abonnement();
        $entityManager = $this->getDoctrine()->getManager();
        if($request ->isMethod("POST")){
            $abonnement->setIdFormation($formation);
            $abonnement->setIdEtudiant($user);
            $abonnement->setRated(0);
            $entityManager->persist($abonnement);
            $entityManager->flush();
            $flashy->success('Vous êtes maintenant abonnée a cette Formation!!', 'hello');

            return $this->redirectToRoute('formation_showsingleformEtudiant',['id' => $formation->getId(), 'idUser' =>$idUser]);

        }
    }
    /**
     * @Route("/desAbonneForm/{idForm}/{idUser}", name="formation_desabonne", methods={"GET","POST"})
     */
    public function desabonneForm($idForm, $idUser , Request $request,  FlashyNotifier $flashy): Response
    {
        $abonnement = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy(array('idFormation' => $idForm,'idEtudiant' => $idUser));
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($idForm);
        $entityManager = $this->getDoctrine()->getManager();
        if($request ->isMethod("POST")){

            $entityManager->remove($abonnement);
            $entityManager->flush();
            $flashy->error('Vous avez désabonnée de cette formation!!' );
            $this->addFlash('delete','Vous avez désabonnée de cette formation!!');
            return $this->redirectToRoute('formation_showsingleformEtudiant',['id' => $formation->getId(), 'idUser' =>$idUser]);
        }
    }

    /**
     * @Route("/{id}/{idUser}/create", name="formation_create", methods={"GET","POST"})
     */
    public function editWithCreate(Request $request, Formation $formation, $idUser,\Swift_Mailer $mailer,  FlashyNotifier $flashy): Response
    {
        $slide1 = $this->getDoctrine()->getRepository(Slide::class)->findBy(array('idFormation' => $formation));
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $slide = new Slide();
        $form2 = $this->createForm(SlideType::class, $slide);
        $form2->handleRequest($request);
            if (($form->isSubmitted() && $form->isValid())) {
            $this->getDoctrine()->getManager()->flush();
                $formData=$form->getData();
                $reponse="  Mr ".$user->getNom()."  votre Formation  ".$formData->getTitre()."  a été crée avec succées!!  ";
                $message = (new \Swift_Message('Ajout Formation'))
                    ->setFrom('esenpai.devnation@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody($reponse);
                $mailer->send($message);
        $flashy->primaryDark('Formation a été ajouté avec succés');
            return $this->redirectToRoute('formation_index');
        }
        $form1 = $this->createForm(SlideType::class, $slide);
        $form1->handleRequest($request);
        if ($form1->isSubmitted()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form1['imageFile']->getData();

            if( $uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/../img';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
                #file name
                $newFilename = $originalFilename.'.'.$uploadedFile->guessExtension();
                $e=$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename

                );
                if($e == 'jpg' ||$e == 'png' || $e == 'jpeg'){
                $slide->setImageSlide($newFilename);
                    $slide->setVideoSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setTextSlide("");
                    $slide->setOrdre(0);
                }elseif($e == 'mp4'){
                    $slide->setVideoSlide($newFilename);
                    $slide->setImageSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setTextSlide("");
                    $slide->setOrdre(0);
                }else{
                    $slide->setTextSlide($newFilename);
                    $slide->setImageSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setVideoSlide("");
                    $slide->setOrdre(0);
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slide);
            $entityManager->flush();
            return $this->redirectToRoute('formation_create',['id' => $formation->getId(),'idUser' =>$idUser]);
        }
        return $this->render('formation/editCreate.html.twig', [
            'formation' => $formation,
            'slide'=> $slide,
            'form' => $form->createView(),
            'form1'=> $form2->createView(),
            'slides' => $slide1
        ]);
    }




    /**
     * @Route("/{id}/edit", name="formation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Formation $formation , \Swift_Mailer $mailer, FlashyNotifier $flashy): Response
    {
        $slide1 = $this->getDoctrine()->getRepository(Slide::class)->findBy(array('idFormation' => $formation));
        $slide = new Slide();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $form1 = $this->createForm(SlideType::class, $slide);
        $form1->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->primaryDark('Formation a été modifié avec succés');

            return $this->redirectToRoute('formation_index');
        }

        if ($form1->isSubmitted()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form1['imageFile']->getData();

            if( $uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/../img';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME);
                #file name
                $newFilename = $originalFilename.'.'.$uploadedFile->guessExtension();
                $e=$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename

                );
                if($e == 'jpg' ||$e == 'png' || $e == 'jpeg'){
                    $slide->setImageSlide($newFilename);
                    $slide->setVideoSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setTextSlide("");
                    $slide->setOrdre(0);
                }elseif($e == 'mp4'){
                    $slide->setVideoSlide($newFilename);
                    $slide->setImageSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setTextSlide("");
                    $slide->setOrdre(0);
                }else{
                    $slide->setTextSlide($newFilename);
                    $slide->setImageSlide("");
                    $slide->setIdFormation($formation);
                    $slide->setVideoSlide("");
                    $slide->setOrdre(0);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slide);
            $entityManager->flush();

            return $this->redirectToRoute('formation_edit',['id' => $formation->getId()]);
        }


        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'form1'=> $form1->createView(),
            'slides' => $slide1
        ]);
    }

    /**
     * @Route("/{id}", name="formation_delete", methods={"POST"})
     */
    public function delete(Request $request, Formation $formation, FlashyNotifier $flashy): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $slides = $entityManager->getRepository(Slide::class)->findBy(array('idFormation' => $formation));
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager1 = $this->getDoctrine()->getManager();
            foreach ($slides as $slide){
            $entityManager1->remove($slide);
            }
            $entityManager1->flush();
            $entityManager2 = $this->getDoctrine()->getManager();
            $flashy->error('Votre Formation a été supprimé' );
            $entityManager2->remove($formation);
            $entityManager2->flush();

        }

        return $this->redirectToRoute('formation_index');
    }
    /**
     * @Route("/{id}", name="formation_showslide", methods={"POST"})
     */
    public function showSlide(Request $request, Slide $slide): Response
    {
        if ($this->isCsrfTokenValid('showslide'.$slide->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

    }


    /**
     * @Route("/delete/{id}", name="formation_delete_admin", methods={"POST"})
     */
    public function deleteFormAdmin(Request $request, Formation $formation): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $slides = $entityManager->getRepository(Slide::class)->findBy(array('idFormation' => $formation));
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager1 = $this->getDoctrine()->getManager();
            foreach ($slides as $slide){
                $entityManager1->remove($slide);
            }
            $entityManager1->flush();
            $entityManager2 = $this->getDoctrine()->getManager();
            $entityManager2->remove($formation);
            $entityManager2->flush();

        }

        return $this->redirectToRoute('accueil_Form_Admin');
    }


}
