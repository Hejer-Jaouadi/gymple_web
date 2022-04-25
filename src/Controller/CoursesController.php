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
use Dompdf\Dompdf;
use Dompdf\Options;


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
     * @Route("/timetable", name="app_time_front", methods={"GET"})
     */
    public function Calendar(CoursesRepository $coursesRepository): Response
    {
        $events = $coursesRepository ->findAll();
        $rdvs =[];

        foreach($events as $event){
            $rdvs[] = [
                'id'=>$event->getId(),
                'start'=>$event->getDate()->format('Y-m-d')." ".$event->getStartTime()->format('H:i'),
                'end'=>$event->getDate()->format('Y-m-d')." ".$event->getEndTime()->format('H:i'),
                'title'=>$event->getCategory()->getName(),
                //'description'=>$event->getTrainer()->getFirstName()." ".$event->getTrainer()->getLastName(),
                //'backgroundColor'=>$event->getBackgroundColor(),
                //'borderColor'=>$event->getBorderColor(),
                //'textColor'=>$event->getTextColor(),
                //'allDay'=>$event->getAllDay()
                


            ];
        }
        $data = json_encode($rdvs);
        return $this->render('courses\time_table.html.twig', compact('data'));
    }
    
    /**
     * @Route("/", name="app_courses_index", methods={"GET"})
     */
    public function index(CoursesRepository $coursesRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $courses = $coursesRepository->findAll();

        $coursepagination = $paginator->paginate($courses, $request->query->getInt('page', 1), 3);

        return $this->render('courses/index.html.twig', [
            'courses' => $coursepagination,
        ]);
    }
    /**
     * @Route("/coursesinfo", name="app_courses_info", methods={"GET"})
     */
    public function courseinfo(coursesRepository $coursesRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $courses = $coursesRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('courses/courseinfo.html.twig', [
            'course' => $courses,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("CoursesList.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('app_courses_info', [], Response::HTTP_SEE_OTHER);

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
