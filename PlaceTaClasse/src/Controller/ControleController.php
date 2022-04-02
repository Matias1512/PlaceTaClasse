<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Entity\Salle;

use App\Entity\Placement;



use App\Form\PlacementType;
use App\Repository\PlaceRepository;
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
    #[Route('/telecharger/{id}', name: 'app_feuilleEmargement_telecharger', methods: ['GET'])]
    public function telecharger(Request $request, Controle $controle, ControleRepository $controleRepository, PlaceRepository $placeRepository, SalleRepository $salleRepository): Response
    {
        
        
        //récupérer le  placement
        $placement = $controle->getPlacement()->toArray();
        $places = array($placeRepository->findById($placement[0]->getPlace()));
        for($i = 1; $i < count($placement);  $i++){
            array_push($places,$placeRepository->findOneById($placement[$i]->getPlace()));
        }
        

        //récupérer les salles
        $salles = array($salleRepository->findById($places[0][0]->getSalle()));
        for($i = 1; $i < count($places);  $i++){
            if(in_array($salles,$placeRepository->findById($places[$i]->getSalle()))){
                array_push($salles,$salleRepository->findById($places[$i]->getSalle()));
            }
        }

        
        $contenu = '<style>.page { width: 95%; height: 95%; }</style>
                    ';
//écriture du fichier testPDF.html
        //pour chaque salle
        for ($i = 0; $i < count($salles);  $i++){
            $contenu .= '<div class="page">';
            
            $contenu.="<h2>IUT DE BAYONNE ET DU PAYS BASQUE</h2>"
                    ."<h4>Département Informatique</h4>";
            $contenu .= '<h4>'.$salles[$i][0]->getNom().'</h4>';
            $path = 'logoIUT.jpeg';
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $contenu .= '<img src="'.$base64.'" alt="image du logo de l\'iut" width="100" height="150"><BR>';

            //nom de la ou les promotion(s)
            $contenu .= $controle->getPromotion()->toArray()[0]->getNomLong();
            for($j = 1; $j < count($controle->getPromotion()->toArray());  $j++){
                $contenu .= '/';
                $contenu .= $controle->getPromotion()->toArray()[$j]->getNomLong();
            }

            //information générale
            $contenu .= '<BR>Enseignant : '.$controle->getReferent()->getNom();
            $contenu .= '<BR>Ressource : ';
            $contenu .= '<BR>Date : '.date_format($controle->getDate(),"d/m/Y");
            $contenu .= '   Heure : '.$controle->getHoraireNonTTDebut().'/'.$controle->getHoraireNonTTFin().'   TT :'.$controle->getHoraireTTDebut().'/'.$controle->getHoraireTTFin();
            $contenu .= '<BR>Surveillant : '.$controle->getSurveillant()->toArray()[$i]->getNom();
            $contenu .= '<table>';
            $contenu .= '<tr>';
            $contenu .=  '<th>Place</th>';
            $contenu .=  '<th>Nom</th>';
            $contenu .=  '<th>Prenom</th>';
            $contenu .=  '<th>Signature</th>';
            $contenu .= '</tr>';

            //premier placement
            $contenu .= '<tr>';
            $contenu .=  '<td>'.$places[0][0]->getNumero().'</td>';
            $contenu .=  '<td>'.$placement[0]->getEtudiant()->getNom().'</td>';
            $contenu .=  '<td>'.$placement[0]->getEtudiant()->getPrenom().'</td>';                
            $contenu .= '</tr>';

            //reste du placement
            for($j = 1; $j < count($placement); $j++){
                $contenu .= '<tr>';
                $contenu .=  '<td>'.$places[$j]->getNumero().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getNom().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getPrenom().'</td>';                
                $contenu .= '</tr>';
            }
            
            $contenu .= '</table>';
            $contenu .= '</div>';
        }
        
        file_put_contents('../templates/testPDF.html', $contenu);

        //téléchargement du fichier au format pdf
        return $this->redirectToRoute('testPDF',["nomFic" => "feuilleEmargement.pdf"]);
    }
    


}


