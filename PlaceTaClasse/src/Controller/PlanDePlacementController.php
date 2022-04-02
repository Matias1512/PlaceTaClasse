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
        $contenu = '<style>.page { width: 95%; height: 95%; }</style>
                    ';
        

        //pour chaque salle
        for ($i = 0; $i < count($salles);  $i++){
            $contenu .= '<div class="page">';
            $contenu .="<h1>BUT INFORMATIQUE</h1>";
            $contenu .= '<h2>'.$salles[$i][0]->getNom().'</h2>';
            
            $path = $salles[$i][0]->getPlan();
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $contenu .= '<img src="'.$base64.'" alt="image de la salle" width="500" height="400"><BR>';

            //nom de la ou les promotion(s)
            $contenu .= $controle->getPromotion()->toArray()[0]->getNomLong();
            for($j = 1; $j < count($controle->getPromotion()->toArray());  $j++){
                $contenu .= '/';
                $contenu .= $controle->getPromotion()->toArray()[$j]->getNomLong();
            }

            //tableau des placement
            $contenu .= '<BR>Les  étudiants  devront  occuper  les  places  indiquées  sur  le  plan  ci-dessus,  conformément  au numéro qui leur a été affecté sur le planning général d’occupation des salles.';
            $contenu .= '<table>';
            $contenu .= '<tr>';
            $contenu .=  '<th>Place</th>';
            $contenu .=  '<th>Nom</th>';
            $contenu .=  '<th>Prenom</th>';
            $contenu .=  '<th>Tiers-Temps</th>';
            $contenu .=  '<th>Ordinateur</th>';
            $contenu .= '</tr>';

            //premier placement
            $contenu .= '<tr>';
            $contenu .=  '<td>'.$places[0][0]->getNumero().'</td>';
            $contenu .=  '<td>'.$placement[0]->getEtudiant()->getNom().'</td>';
            $contenu .=  '<td>'.$placement[0]->getEtudiant()->getPrenom().'</td>';
            if ($placement[0]->getEtudiant()->getTierTemps()){
                $contenu .=  '<td>Tiers-Temps</td>';
                if ($placement[0]->getEtudiant()->getOrdinateur()){
                    $contenu .=  '<td>Ordinateur</td>';
                }
            }
                
            $contenu .= '</tr>';

            //reste du placement
            for($j = 1; $j < count($placement); $j++){
                $contenu .= '<tr>';
                $contenu .=  '<td>'.$places[$j]->getNumero().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getNom().'</td>';
                $contenu .=  '<td>'.$placement[$j]->getEtudiant()->getPrenom().'</td>';
                if ($placement[$j]->getEtudiant()->getTierTemps()){
                    $contenu .=  '<td>Tiers-Temps</td>';
                    if ($placement[$j]->getEtudiant()->getOrdinateur()){
                        $contenu .=  '<td>+ Ordinateur</td>';
                    }
                }
                
                $contenu .= '</tr>';
            }
            
            $contenu .= '</table>';
            $contenu .= '</div>';
        }
        
        file_put_contents('../templates/testPDF.html', $contenu);

        //téléchargement du fichier au format pdf
        return $this->redirectToRoute('testPDF',["nomFic" => "planDePlacemnt.pdf"]);
    }
}

?>
