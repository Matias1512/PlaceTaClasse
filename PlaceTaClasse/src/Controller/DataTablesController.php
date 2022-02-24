<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataTablesController extends AbstractController
{
    /**
     * @Route("/data/tables", name="data_tables")
     */
    public function index(): Response
    {
        return $this->render('data_tables/index.html.twig', [
            'controller_name' => 'DataTablesController',
        ]);
    }
}
