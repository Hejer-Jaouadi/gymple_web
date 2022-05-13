<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Gym;
use App\Form\RoomType;
use App\Repository\GymRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{

    /**
     * @Route("/api/AllRoom", name="api_AllRoom")
     */

    public function AllRooms(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Room::class);
        $room = $repository->findAll();
        $jsonContent = $Normalizer->normalize($room, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getRoomById/{id}", name="api_getRoom")
     */

    public function getRoomById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Room::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteRoom/{id}", name="api_deleteRoom")
     */

    public function deleteroom(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Room::class);
        $p = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response("Room deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createRoom", name="api_createRoom")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $room = new Room();

        $em = $this->getDoctrine()->getManager();
        $gym = $em->getRepository(Gym::class)->find($request->get('idgym'));


        $room->setRoomname($request->get('roomName'));
        $room->setRoomnumber($request->get('roomNumber'));
        $room->setMaxNbr($request->get('maxNbr'));
        $room->setIdgym($gym);



        $em->persist($room);
        $em->flush();

        $jsonContent = $Normalizer->normalize($room, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/updateRoom/{id}", name="api_updateRoom")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $room = $em->getRepository(Room::class)->find($id);

        $room->setRoomname($request->get('roomName'));
        $room->setRoomnumber($request->get('roomNumber'));
        $room->setMaxNbr($request->get('maxNbr'));


        $em->flush();

        $jsonContent = $Normalizer->normalize($room, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
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
