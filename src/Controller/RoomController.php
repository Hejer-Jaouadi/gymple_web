<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\GymRepository;
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
     * @Route("/stats", name="app_room_stats")
     */
    public function statistics(RoomRepository $roomRepository, GymRepository $gymRepository): response
    {
        $gyms = $gymRepository->findAll();
        $rooms = $roomRepository->findAll();

        $roomName = [];
        $roomCount = [];
        


        return $this->render('room/stats.html.twig', [
            'gyms' => $gyms,
            'rooms' => $rooms,
        ]);

    }

    /**
     * @Route("/", name="app_room_index", methods={"GET" , "POST"})
     */
    public function index(RoomRepository $roomRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'roomName':
                        $rooms = $roomRepository->SortByName();
                        break;

                    case 'roomNumber':
                       $rooms= $roomRepository->SortByNumber();
                        break;


                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'roomName':
                        $rooms= $roomRepository->findByName($value);
                        break;

                    case 'roomNumber':
                        $rooms = $roomRepository->findByNumber($value);
                        break;

                    case 'maxNbr':
                        $rooms = $roomRepository->findByCapacity($value);
                        break;

                }
            }
            $roompagination = $paginator->paginate(
                $rooms,
                $request->query->getInt('page',1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5
            );

            return $this->render('room/index.html.twig', [
                'rooms' => $roompagination,
            ]);

        }

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



}
