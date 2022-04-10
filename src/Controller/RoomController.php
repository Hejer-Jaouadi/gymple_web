<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="app_room_index", methods={"GET"})
     */
    public function index(RoomRepository $roomRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $rooms =  $roomRepository->findAll();

        $roompagination = $paginator->paginate(
            $rooms, // on passe les donnees
            $request->query->getInt('page',1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5
        );

        return $this->render('room/index.html.twig', [
            'rooms' => $roompagination,
        ]);
    }

    /**
     * @Route("/new", name="app_room_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RoomRepository $roomRepository): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roomRepository->add($room);
            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_room_show", methods={"GET"})
     */
    public function show(Room $room): Response
    {
        return $this->render('room/show.html.twig', [
            'room' => $room,
        ]);
    }

    /**
     * @Route("/{idr}/edit", name="app_room_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roomRepository->add($room);
            return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idr}", name="app_room_delete", methods={"POST"})
     */
    public function delete(Request $request, Room $room, RoomRepository $roomRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getIdr(), $request->request->get('_token'))) {
            $roomRepository->remove($room);
        }

        return $this->redirectToRoute('app_room_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/stats", name="app_room_stats")
     */
    public function statistics(): response
    {
        return $this->render('room/stats.html.twig');
    }

}
