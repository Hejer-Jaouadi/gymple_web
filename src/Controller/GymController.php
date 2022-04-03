<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Form\GymType;
use App\Repository\GymRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gym")
 */
class GymController extends AbstractController
{
    /**
     * @Route("/", name="app_gym_index", methods={"GET"})
     */
    public function index(GymRepository $gymRepository): Response
    {
        return $this->render('gym/index.html.twig', [
            'gyms' => $gymRepository->findAll(),
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
