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
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/gym")
 */
class GymController extends AbstractController
{

    /**
     * @Route("/api/AllGyms", name="api_AllGyms")
     */

    public function AllGyms(NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Gym::class);
        $gym = $repository->findAll();
        $jsonContent = $Normalizer->normalize($gym, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/api/getGymById/{id}", name="api_getgym")
     */

    public function getGymById(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $p = $repository->find($id);
        $jsonContent = $Normalizer->normalize($p, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/deleteGym/{id}", name="api_deletegym")
     */

    public function deleteGym(NormalizerInterface $Normalizer, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Gym::class);
        $g = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($g);
        $em->flush();
        $jsonContent = $Normalizer->normalize($g, 'json', ['groups' => 'post:read']);

        return new Response("gym deleted" . json_encode($jsonContent));
    }

    /**
     * @Route("/api/createGym", name="api_creategym")
     */
    public function create(Request $request, NormalizerInterface $Normalizer)
    {
        $gym = new Gym();

        $em = $this->getDoctrine()->getManager();

        $gym->setLocation($request->get('location'));
        $gym->setFacilities($request->get('facilities'));


        $em->persist($gym);
        $em->flush();

        $jsonContent = $Normalizer->normalize($gym, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/api/updateGym/{id}", name="api_updategym")
     */
    public function update(Request $request, NormalizerInterface $Normalizer, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $gym = $em->getRepository(Gym::class)->find($id);

        $gym->setLocation($request->get('location'));
        $gym->setFacilities($request->get('facilities'));



        $em->flush();

        $jsonContent = $Normalizer->normalize($gym, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /**
         * @Route("/all/gyms", name="gyms_mobile", methods={"GET"})

    public function mobile_gyms(GymRepository $gymRepository)
    {
        $gyms = $gymRepository->findAll();
        $serialzer = new Serializer([new ObjectNormalizer()]);
        $formatted  = $serializer->normalize($gyms);
        return new JsonResponse($formatted);
    }
*/

       /**
        * @Route("/all/gyms", name="gyms_mobile", methods={"GET"})
        */
    public function mobile_all_gyms(NormalizerInterface $normalizable,GymRepository $gymRepository)
    {
        $gyms = $gymRepository->findAll();
        $jsonContent = $normalizable->normalize($gyms, 'json' , [ 'groups'=> 'read:gyms' ]);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/stats", name="app_gym_stats")
     */
    public function statistics(RoomRepository $roomRepository, GymRepository $gymRepository): response
    {
        $gyms = $gymRepository->findAll();


        $gymName = [];
        $gymCount = [];
        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach ($gyms as $gym) {
            $gymName[] = $gym->getLocation();
            $gymCount[] = count($gym->getRooms());
        }


        return $this->render('gym/stats.html.twig', [
            'gymName' => json_encode($gymName),
            'gymCount' => json_encode($gymCount)

        ]);

    }

    /**
     * @Route("/gymfront", name="app_gym_front", methods={"GET", "POST"})
     */
    public function gymFront(GymRepository $gymRepository, RoomRepository $roomRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if ( $request->isMethod('POST')) {




                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'location':
                        $gyms = $gymRepository->findByLocation($value);
                        $rooms= $roomRepository->findAll();
                        break;

                    case 'facilities':
                        $gyms = $gymRepository->findByFacilities($value);
                        $rooms= $roomRepository->findAll();
                        break;


                }



            $gymspagination = $paginator->paginate(
                $gyms, // on passe les donnees
                $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                2
            );

            return $this->render('gym/gymfront.html.twig', [
                'gyms' => $gymspagination,
                'rooms' => $rooms,
            ]);
        }

        $gyms = $gymRepository->findAll();
        $rooms = $roomRepository->findAll();


        $gymspagination = $paginator->paginate(
            $gyms, // on passe les donnees
            $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2
        );

        return $this->render('gym/gymfront.html.twig', [
            'gyms' => $gymspagination,
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
     * @Route("/", name="app_gym_index", methods={"GET" , "POST"})
     */
    public function index(GymRepository $gymRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'location':
                        $gyms = $gymRepository->SortByLocation();
                        break;

                    case 'facilities':
                        $gyms= $gymRepository->SortByFacilities();
                        break;


                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'location':
                        $gyms = $gymRepository->findByLocation($value);
                        break;

                    case 'facilities':
                        $gyms = $gymRepository->findByFacilities($value);
                        break;


                }
            }


            $gymspagination = $paginator->paginate(
                $gyms, // on passe les donnees
                $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5
            );

            return $this->render('gym/index.html.twig', [
                'gyms' => $gymspagination,

            ]);
        }

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
