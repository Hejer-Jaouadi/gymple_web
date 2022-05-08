<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="showReservations")
     */
    public function index(): Response
    {
        $reservations = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->findAll();
        return $this->render('reservation/index.html.twig', [
                'r'=>$reservations
        ]);
    }

    /**
     * @Route("/admin", name="display_admin")
     */
    public function indexAdmin(): Response
    {

        return $this->render('Admin/index.html.twig'
        );
    }

    /**
     * @Route("/client", name="display_client")
     */
    public function indexClient(): Response
    {

        return $this->render('Client/index.html.twig');
    }

    /**
     * @Route("/addReservation", name="add_reservation")
     */
    public function addReservation(Request $request): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);//Add
            $em->flush();

            return $this->redirectToRoute('showReservations');
        }
        return $this->render('reservation/createReservation.html.twig', ['f' => $form->createView()]);

    }

    /**
     * @Route("/deleteReservation/{id}", name="delete_reservation")
     */
    public function deleteReservation(Reservation $reservation): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('showReservations');
    }

    /**
     * @Route("/updateReservation/{id}", name="updateReservation")
     */
    public function updateReservation(Request $request,$id): Response
    {
        $blog = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);

        $form = $this->createForm(ReservationType::class,$blog);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('showReservations');
        }
        return $this->render('reservation/updateReservation.html.twig',['f'=>$form->createView()]);




    }
}
