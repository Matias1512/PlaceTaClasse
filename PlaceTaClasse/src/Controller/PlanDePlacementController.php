<?php

namespace App\PlanDePlacement;

use App\Entity\Placement;
use App\Form\PlacementType;
use App\Repository\PlacementRepository;
use App\Repository\ControleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planDePlacement')]
class PlanDePlacementController extends AbstractController
{
    #[Route('/controleSameTime', name: 'app_plandeplacement_new', methods: ['GET'])]
    public function new(Request $request, Controle $controle, ControleRepository $controleRepository): Response
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
}

?>
