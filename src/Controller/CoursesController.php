<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Form\CoursesType;
use App\Repository\CoursesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/courses")
 */
class CoursesController extends AbstractController
{
   
    /**
     * @Route("/coursefront", name="app_courses_front", methods={"GET"})
     */
    public function courseFront(CoursesRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        
        return $this->render('courses/course_front.html.twig', [
            'courses' => $courses,
            
        ]);
    }
    
    /**
     * @Route("/", name="app_courses_index", methods={"GET"})
     */
    public function index(CoursesRepository $coursesRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $courses = $coursesRepository->findAll();

        $coursepagination = $paginator->paginate($courses, $request->query->getInt('page', 1), 5);

        return $this->render('courses/index.html.twig', [
            'courses' => $coursepagination,
        ]);
    }

    /**
     * @Route("/new", name="app_courses_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CoursesRepository $coursesRepository): Response
    {
        $course = new Courses();
        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursesRepository->add($course);
            return $this->redirectToRoute('app_courses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courses/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_courses_show", methods={"GET"})
     */
    public function show(Courses $course): Response
    {
        return $this->render('courses/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_courses_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Courses $course, CoursesRepository $coursesRepository): Response
    {
        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursesRepository->add($course);
            return $this->redirectToRoute('app_courses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('courses/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_courses_delete", methods={"POST"})
     */
    public function delete(Request $request, Courses $course, CoursesRepository $coursesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $coursesRepository->remove($course);
        }

        return $this->redirectToRoute('app_courses_index', [], Response::HTTP_SEE_OTHER);
    }
}
