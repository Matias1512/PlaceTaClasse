<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\EntrepriseFormType;
use App\Form\StageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;



class testPDF extends AbstractController
{
    /**
     * @Route("/testPDF", name="testPDF")
     */
    public function index()
    {
        
        require_once 'dompdf_1-2-1/dompdf/autoload.inc.php';
            
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml(file_get_contents('../templates/testPDF.html'));
            
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper("a4", 'landscape');
            
        // Render the HTML as PDF 
        $dompdf->render();
            
        // Output the generated PDF to Browser
        $dompdf->stream("planDePlacement.pdf", ["Attachement" => true]);
        
        exit(0);
        
        

    }
}
