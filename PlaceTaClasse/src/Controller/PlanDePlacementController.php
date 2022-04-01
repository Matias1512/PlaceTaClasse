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
            if(!in_array($placeRepository->findById($places[$i]->getSalle()),$salles)){
                array_push($salles,$salleRepository->findById($places[$i]->getSalle()));
            }
        }

        //écriture du fichier testPDF.html
        $contenu="<h1>BUT INFORMATIQUE</h1>";
        for ($i = 0; $i < count($salles);  $i++){
            $contenu .= '<img src="'.$salles[$i][0]->getPlan().'" alt="image de la salle">';
        }
        
        file_put_contents('../templates/testPDF.html', $contenu);

        //téléchargement du fichier au format pdf
        return $this->redirectToRoute('testPDF');
    }
}

?>
