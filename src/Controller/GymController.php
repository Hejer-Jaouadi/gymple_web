<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Form\GymType;
use App\Repository\GymRepository;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/gym")
 */
class GymController extends AbstractController
{
    /**
     * @Route("/gymfront", name="app_gym_front", methods={"GET"})
     */
    public function gymFront(GymRepository $gymRepository , RoomRepository $roomRepository): Response
    {
        $gyms = $gymRepository->findAll();
        $rooms = $roomRepository->findAll();
        return $this->render('gym/gymfront.html.twig', [
            'gyms' => $gyms,
            'rooms' => $rooms,
        ]);
    }

    /**
     * @Route("/gyminfo", name="app_gym_info", methods={"GET"})
     */
    public function gyminfo(GymRepository $gymRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $gyms = $gymRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('gym/gyminfo.html.twig', [
            'gyms' => $gyms,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("GymList.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('app_gym_info', [], Response::HTTP_SEE_OTHER);

    }


    /**
     * @Route("/maps", name="app_gym_maps", methods={"GET"})
     */
    public function maps(GymRepository $gymRepository): Response
    {
        return $this->render('gym/maps.html.twig', [
            'gyms' => $gymRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="app_gym_index", methods={"GET"})
     */
    public function index(GymRepository $gymRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $gyms = $gymRepository->findAll();

        $gymspagination = $paginator->paginate(
            $gyms, // on passe les donnees
            $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5
        );

        return $this->render('gym/index.html.twig', [
            'gyms' => $gymspagination,
        ]);
    }

    /**
     * @Route("/new", name="app_gym_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gym = new Gym();
        $form = $this->createForm(GymType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gym);
            $entityManager->flush();
            return $this->redirectToRoute('app_gym_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gym/new.html.twig', [
            'gym' => $gym,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idg}", name="app_gym_show", methods={"GET"})
     */
    public function show(Gym $gym): Response
    {
        return $this->render('gym/show.html.twig', [
            'gym' => $gym,
        ]);
    }

    /**
     * @Route("/{idg}/edit", name="app_gym_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Gym $gym, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GymType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gym);
            $entityManager->flush();
            return $this->redirectToRoute('app_gym_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('gym/edit.html.twig', [
            'gym' => $gym,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/{idg}", name="app_gym_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Gym $gym, GymRepository $gymRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gym->getIdg(), $request->get('_token'))) {
            $gymRepository->remove($gym);
        }

        return $this->redirectToRoute('app_gym_index', [], Response::HTTP_SEE_OTHER);
    }



}
