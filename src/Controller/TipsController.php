<?php

namespace App\Controller;

use App\Entity\Tips;
use App\Entity\Category;
use App\Form\TipsType;
use App\Repository\TipsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/tips")
 */
class TipsController extends AbstractController
{
    /**
     * @Route("/", name="app_tips_index", methods={"GET"})
     */
    public function index(TipsRepository $tipsRepository): Response
    {
        return $this->render('tips/index.html.twig', [
            'tips' => $tipsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_tips_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TipsRepository $tipsRepository): Response
    {
        $tip = new Tips();
        $form = $this->createForm(TipsType::class, $tip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipsRepository->add($tip);
            return $this->redirectToRoute('app_tips_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tips/new.html.twig', [
            'tip' => $tip,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/tipsinfo/{tip}", name="app_tips_info", methods={"GET"})
     */
    public function tipinfo(TipsRepository $tipsRepository,$tip): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        if ($tip) {
            $tips = $tipsRepository->findById($tip);
        }
        else {
            $tips = $tipsRepository->findAll();
        }
        

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('tips/tipseinfo.html.twig', [
            'tip' => $tips,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("SavedTip.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('app_tips_info', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/tipfront/{category}", name="app_tips_front", methods={"GET"})
     */
    public function tipFront(TipsRepository $tipRepository, $category): Response
    {
        echo $category;
        $tips = $tipRepository->findByCategory($category);
        
        return $this->render('tips/tips_front.html.twig', [
            'tips' => $tips,
            
        ]);
    }


    /**
     * @Route("/{id}", name="app_tips_show", methods={"GET"})
     */
    public function show(Tips $tip): Response
    {
        return $this->render('tips/show.html.twig', [
            'tip' => $tip,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tips_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tips $tip, TipsRepository $tipsRepository): Response
    {
        $form = $this->createForm(TipsType::class, $tip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tipsRepository->add($tip);
            return $this->redirectToRoute('app_tips_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tips/edit.html.twig', [
            'tip' => $tip,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tips_delete", methods={"POST"})
     */
    public function delete(Request $request, Tips $tip, TipsRepository $tipsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tip->getId(), $request->request->get('_token'))) {
            $tipsRepository->remove($tip);
        }

        return $this->redirectToRoute('app_tips_index', [], Response::HTTP_SEE_OTHER);
    }
    }
