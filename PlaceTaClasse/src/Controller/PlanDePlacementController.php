<?php

namespace App\Controller;

use App\Entity\Placement;
use App\Entity\Controle;
use App\Entity\Place;
use App\Entity\Salle;
use App\Form\PlacementType;
use App\Repository\PlacementRepository;
use App\Repository\PlaceRepository;
use App\Repository\ControleRepository;
use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planDePlacement')]
class PlanDePlacementController extends AbstractController
{
    #[Route('/telecharger/{id}', name: 'app_plandeplacement_telecharger', methods: ['GET'])]
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

        //écriture du fichier testPDF.html
        $contenu="<h1>BUT INFORMATIQUE</h1>";
        for ($i = 0; $i < count($salles);  $i++){
            $contenu .= '<h2>'.$salles[$i][0]->getNom().'</h2>';
            $contenu .= '<img src="'.$salles[$i][0]->getPlan().'" alt="image de la salle"><BR>';
            $contenu .= $controle->getPromotion()->toArray()[0]->getNomLong();
            for($j = 1; $j < count($controle->getPromotion()->toArray());  $j++){
                $contenu .= '/';
                $contenu .= $controle->getPromotion()->toArray()[$j]->getNomLong();
            }
            $contenu .= '<BR>Les  étudiants  devront  occuper  les  places  indiquées  sur  le  plan  ci-dessus,  conformément  au numéro qui leur a été affecté sur le planning général d’occupation des salles.';
            $contenu .= '<table>';
            $contenu .= '<tr>';
            $contenu .=  '<th>Place</th>';
            $contenu .=  '<th>Nom</th>';
            $contenu .=  '<th>Prenom</th>';
            $contenu .=  '<th>Tiers-Temps</th>';
            $contenu .=  '<th>Ordinateur</th>';
            $contenu .= '</tr>';
            for($j = 1; $j < count($placement); $j++){
                $contenu .= '<tr>';
                $contenu .=  '<td>'.$places[$j]->getNumero().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getNom().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getPrenom().'</td>';
                if ($placement[$j]->getEtudiant()->getTierTemps()){
                    $contenu .=  '<td>Tiers-Temps</td>';
                    if ($placement[$j]->getEtudiant()->getOrdinateur()){
                        $contenu .=  '<td>Ordinateur</td>';
                    }
                }
                
                $contenu .= '</tr>';
            }
            
            $contenu .= '</table>';
        }
        
        file_put_contents('../templates/testPDF.html', $contenu);

        //téléchargement du fichier au format pdf
        return $this->redirectToRoute('testPDF');
    }
}

?>
