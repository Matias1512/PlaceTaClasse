<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Entity\Etudiant;
use App\Form\PromotionType;
use App\Repository\EtudiantRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/promotion')]
class PromotionController extends AbstractController
{
    #[Route('/', name: 'app_promotion_index', methods: ['GET'])]
    public function index(PromotionRepository $promotionRepository): Response
    {
    
        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PromotionRepository $promotionRepository): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionRepository->add($promotion);
            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_promotion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promotion $promotion, PromotionRepository $promotionRepository): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionRepository->add($promotion);
            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, PromotionRepository $promotionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $promotionRepository->remove($promotion);
        }

        return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/import/{nomLong}/{nomCourt}', name: 'app_promotion_import_promo', methods: ['GET'])]
    public function import_promo(String $nomLong, String $nomCourt, EntityManagerInterface $manager): Response
    { 

        $promotion = new Promotion();
        $promotion->setNomCourt($nomCourt);
        $promotion->setNomLong($nomLong);

        $manager->persist($promotion);
        $manager->flush();
        return new Response();
    }
        
    #[Route('/import/{nomLong}/{nomCourt}/{nom}/{prenom}/{mail}/{TP}/{TT}/{Ordinateur}', name: 'app_promotion_import_etudiant', methods: ['GET'])]
    public function import_etudiant(String $nomLong, String $nomCourt, String $nom, String $prenom,int $TP,bool $TT,bool $Ordinateur, String $mail, EntityManagerInterface $manager): Response
    { 

        $etud =  new Etudiant();
        $etud->setNom($nom);
        $etud->setPrenom($prenom);
        $etud->setMail($mail);
        $etud->setTp($TP);
        $etud->setTierTemps($TT);
        $etud->setOrdinateur($Ordinateur);
        $RepositoryPromotion = $manager->getRepository(Promotion::class);
        $RepositoryEtudiant = $manager->getRepository(Etudiant::class);
        $promotion = $RepositoryPromotion->findBy(array("nomCourt" => $nomCourt, "nomLong" => $nomLong));
        $etud->setPromotion($promotion[0]);
        $manager->persist($etud);
        $manager->flush();
        return new Response();
    }
}
