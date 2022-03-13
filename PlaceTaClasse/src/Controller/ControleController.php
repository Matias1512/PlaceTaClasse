<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
