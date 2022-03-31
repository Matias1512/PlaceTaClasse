<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Salle;
use App\Entity\Promotion;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use App\Repository\PromotionRepository;
use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;



use App\Entity\Enseignant;
use App\Entity\Module;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/controle')]
class ControleController extends AbstractController
{
    #[Route('/', name: 'app_controle_index', methods: ['GET'])]
    public function index(ControleRepository $controleRepository): Response
    {
        return $this->render('controle/index.html.twig', [
            'controles' => $controleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_controle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ControleRepository $controleRepository): Response
    {
        $controle = new Controle();
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $controleRepository->add($controle);
            return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controle/new.html.twig', [
            'controle' => $controle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controle_show', methods: ['GET'])]
    public function show(Controle $controle): Response
    {
        return $this->render('controle/show.html.twig', [
            'controle' => $controle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_controle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Controle $controle, ControleRepository $controleRepository): Response
    {
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $controleRepository->add($controle);
            return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controle/edit.html.twig', [
            'controle' => $controle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controle_delete', methods: ['POST'])]
    public function delete(Request $request, Controle $controle, ControleRepository $controleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controle->getId(), $request->request->get('_token'))) {
            $controleRepository->remove($controle);
        }

        return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/sameTime', name: 'app_controle_sameTime', methods: ['GET'])]
    public function sameTime(Request $request, Controle $controle, ControleRepository $controleRepository): Response
    {
        $tabControleSD = array(); //tab controle en meme temps
        $controlesSD = $controleRepository->findControleSameDate($controle->Date);
        
        $heure = $controle->HoraireTTDebut;
        $heures = explode(":", $heure);
        $heure = intval($heures[0]);

        if($heure >= 8 && $heure <= 12){
            foreach($controlesSD as $c){
                $heure = $c->HoraireTTDebut;
                $heures = explode(":", $heure);
                $heure = intval($heures[0]);
                if($heure >= 8 && $heure <= 12){
                    array_push($tabControleSD, $c);
                }
            }
        }
        else{
            foreach($controlesSD as $c){
                $heure = $c->HoraireTTDebut;
                $heures = explode(":", $heure);
                $heure = intval($heures[0]);
                if($heure >= 14){
                    array_push($tabControleSD, $c);
                }
            }
        }
        return $this->render('planDePlacement/controleSameTime.html.twig', ['tabControleSD'=>$tabControleSD]);
    }

    #[Route('/{id}/recupSalle', name: 'app_controle_recupSalle', methods: ['GET', 'POST'])]
    public function recupSalle(Request $request, Controle $controle, PromotionRepository $PromotionRepository, SalleRepository $SalleRepository, ControleRepository $ControleRepository ): Response
    {
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
                        ->add('Salle', EntityType::class, ['class' => Salle::class, 'choice_label' => 'nom', 'multiple' => true,'expanded' => true,])
                        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            
            //Initialisation 
            $data = $form->getData();
            $tabSalles = $data['Salle'];

            $promotion = $controle->getPromotion()[0];

            $cptEtudPlace = 0;
            $cptPlaceAttribuee =0;
            $nbEtudiants= count($promotion->getEtudiants());
            $nbPlaceDispo =0;
            foreach($tabSalles as $salle )
            {
                $nbPlaceDispo += $salle->getNbPlace();
            }
            
            //Séparer les triso des normaux

            $tabEtudNonTT = [];
            $tabEtudTT = [];

            foreach($promotion->getEtudiants() as $etudiant)
            {
                if($etudiant->getTierTemps() == true)
                {
                    array_push($tabEtudTT,$etudiant);
                }
                else
                {
                    array_push($tabEtudNonTT, $etudiant);
                }
            }
            




            return $this->render('test.html.twig', ['data'=>$tabSalles, 'controleId'=>$controle->getId(), 'nb'=>$nbPlaceDispo, 'TabNonTT'=>$tabEtudNonTT]);


            

        }

        // ... render the form
        return $this->render('planDePlacement/controleSameTime.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    
    


    }


