<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Salle;

use App\Entity\Place;
use App\Entity\Promotion;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use App\Repository\PromotionRepository;
use App\Repository\SalleRepository;
use App\Repository\PlacementRepository;
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
use App\Entity\Placement;
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
    public function recupSalle(Request $request, Controle $controle, PromotionRepository $PromotionRepository, SalleRepository $SalleRepository, ControleRepository $ControleRepository, PlacementRepository $PlacementRepository, EntityManagerInterface $manager): Response
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
            
            //Séparer les TT des nons TT

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

            //Déterminer s'il y a assez de place
                
            $nbOrdinateur =0;
            if($nbPlaceDispo<$nbEtudiants)
            {
                exit("Il n'y a pas assez de place disponible");
            }
            else
            {
                foreach($tabEtudTT as $etudiant)
                {
                    if($etudiant->getOrdinateur()==true)
                    {
                        $nbOrdinateur = $nbOrdinateur +1;
                    }
                }


            }
            
            
            //Choisir la salle qui accueil les tiers temps
            
            $numSalleTT= 1;
            foreach($tabSalles as $salle)
            {
                $tabPrise= explode(',', $salle->getEmplacementPrise());
                $nbPrise = count($tabPrise);
                if($nbOrdinateur < $nbPrise)
                {
                    $numSalleTT = $salle->getId();   
                    break;
                }
            
            }

            //Placer les étudiants

            //Placer les TT et Ordinateurs
            $tabPlaceOccupee= [];
            $parcoursTabPrise=0;
            $parcoursSalle =0;
            $salle = $SalleRepository->find($numSalleTT);

            foreach($tabEtudTT as $etudiant)
            {
                $placement = new Placement();
                if($etudiant->getOrdinateur() == true)
                {
                    //Placer les TT avec Ordinateur
                    $place= $salle->getPlaces()[$tabPrise[$parcoursTabPrise]-1];
                    $placement->setPlace($place);
                    $parcoursTabPrise ++;
                    array_push($tabPlaceOccupee, $place);

                }
                else{

                    $place= $salle->getPlaces()[$parcoursSalle];
                    while(in_array($place, $tabPlaceOccupee))
                    {
                        $parcoursSalle++ ;
                        $place= $salle->getPlaces()[$parcoursSalle];
                    }
                    $placement->setPlace($place);
                    $parcoursSalle ++ ;
                    array_push($tabPlaceOccupee, $place);
                }

                    $placement->setEtudiant($etudiant);
                    $placement->addControle($controle);
                    $PlacementRepository->add($placement);
            }

            //Placer les non TT
            $cptEtudPlace = 0;
            foreach($tabSalles as $salle)
            {
                //vérifier si la salle est la même que celle des TT
                if($salle->getId() == $numSalleTT){
                    $places = $salle->getPlaces();
                    foreach($places as $place)
                    {
                        if($cptEtudPlace < count($tabEtudNonTT)){
                            //vérifier si la place n'est pas déjà occuper
                            if(!(in_array($place, $tabPlaceOccupee))){
                                $placement = new Placement();
                                $placement->setEtudiant($tabEtudNonTT[$cptEtudPlace]);
                                $placement->setPlace($place);
                                $placement->addControle($controle);
                                $cptEtudPlace++;
                                $PlacementRepository->add($placement);
                            }
                        }
                    }
                }
                else{
                    $places = $salle->getPlaces();
                    foreach($places as $place){
                        if($cptEtudPlace < count($tabEtudNonTT)){
                            $placement = new Placement();
                            $placement->setEtudiant($tabEtudNonTT[$cptEtudPlace]);
                            $placement->setPlace($place);
                            $placement->addControle($controle);
                            $cptEtudPlace++;
                            $PlacementRepository->add($placement);
                        }
                    }
                }   
            }
            return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
        }

        // ... render the form
        return $this->render('planDePlacement/controleSameTime.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    
    


    }


