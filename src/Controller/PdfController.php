<?php

namespace App\Controller;

use App\Repository\CommandsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends AbstractController
{
    /**
     * @Route("/facture", name="facture_index",methods={"GET"})
     */
    public function index(CommandsRepository $commandsRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commands = $commandsRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('cart/facture.html.twig', [
            'title' => "Welcome to our PDF Test",
            'commands'=>$commands,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        // Render the HTML as PDF
        $dompdf->stream("facture.pdf",["Attachment"=>true]);





        // Send some text response
        return new Response("The PDF file has been succesfully generated !");
    }

}
