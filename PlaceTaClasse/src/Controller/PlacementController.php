<?php

namespace App\Controller;

use App\Entity\Placement;
use App\Form\PlacementType;
use App\Repository\PlacementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/placement')]
class PlacementController extends AbstractController
{
    #[Route('/', name: 'app_placement_index', methods: ['GET'])]
    public function index(PlacementRepository $placementRepository): Response
    {
        return $this->render('placement/index.html.twig', [
            'placements' => $placementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_placement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlacementRepository $placementRepository): Response
    {
        $placement = new Placement();
        $form = $this->createForm(PlacementType::class, $placement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placementRepository->add($placement);
            return $this->redirectToRoute('app_placement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('placement/new.html.twig', [
            'placement' => $placement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_placement_show', methods: ['GET'])]
    public function show(Placement $placement): Response
    {
        return $this->render('placement/show.html.twig', [
            'placement' => $placement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_placement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Placement $placement, PlacementRepository $placementRepository): Response
    {
        $form = $this->createForm(PlacementType::class, $placement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $placementRepository->add($placement);
            return $this->redirectToRoute('app_placement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('placement/edit.html.twig', [
            'placement' => $placement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_placement_delete', methods: ['POST'])]
    public function delete(Request $request, Placement $placement, PlacementRepository $placementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$placement->getId(), $request->request->get('_token'))) {
            $placementRepository->remove($placement);
        }

        return $this->redirectToRoute('app_placement_index', [], Response::HTTP_SEE_OTHER);
    }
}
