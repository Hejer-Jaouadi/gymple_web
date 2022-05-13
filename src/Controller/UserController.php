<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\User2;
use App\Form\UserType;
use App\Form\CodeType;
use App\Form\editType;
use App\Form\RegisterType;
use App\Form\AdminType;
use App\Form\EmailType;
use App\Form\Login;
use App\Form\User3;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\TrainerType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\MemberType;
use App\Controller\MailerController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Membership;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    
    /**
     * @Route("/", name="app_user_index", methods={"GET", "POST"})
     */
    public function index(UserRepository $rep,EntityManagerInterface $entityManager,SessionInterface $session,PaginatorInterface $paginator,Request $request): Response
    {
        if ( $request->isMethod('POST')) {

            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'role':
                        $users = $rep->SortByRole();
                        break;

                    case 'email':
                        $users= $rep->SortByEmail();
                        break;


                }
            }
            else{
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'last':
                        $users = $rep->lastfind($value);
                        break;

                    case 'email':
                        $users = $rep->emailfind($value);
                        break;
            }
            $userspagination = $paginator->paginate(
                $users, // on passe les donnees
                $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5
            );

        return $this->render('user/index.html.twig', [
            'users' => $userspagination,
        ]);
        }
        
    }    
        $user=$session->get('user');
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
            $userspagination = $paginator->paginate(
                $users, // on passe les donnees
                $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                5
            );

        return $this->render('user/index.html.twig', [
            'users' => $userspagination,
        ]);
    }

    /**
     * @Route("/excel", name="app_user_excel", methods={"GET"})
     */
    public function excel(Request $request,UserRepository $rep,EntityManagerInterface $entityManager):Response
    {
        $spreadsheet = new Spreadsheet();
        $users= $rep
        ->getAll();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $i = 2;
        foreach ($users as $user){
            $sheet->setCellValue('A'.$i, $user->getRole());
            $sheet->setCellValue('B'.$i, $user->getFirstName());
            $sheet->setCellValue('C'.$i, $user->getLastName());
            $sheet->setCellValue('D'.$i, $user->getEmail());
            $i++;
        }
        $sheet->setTitle("Users Data")->setCellValue('A1', 'Role')
        ->setCellValue('B1', 'First Name')
        ->setCellValue('C1', 'Last Name')
        ->setCellValue('D1', 'Email');
        
        
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        
        // In this case, we want to write the file in the public directory
        $publicDirectory=$this->getParameter('kernel.project_dir') . '/public';
        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . '/excel_symfony4.xlsx';
        
        // Create the file
        $writer->save($excelFilepath);
        
        // Return a text response to the browser saying that the excel was succesfully created
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * @Route("/password", name="app_user_pass", methods={"GET", "POST"})
     */
    public function code(Request $request, EntityManagerInterface $entityManager,MailerInterface $m,SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(CodeType::class, $user);
        $form->handleRequest($request);
        $session = $request->getSession();
        $user=$session->get('user')[0];
        

        if ($form->isSubmitted()) {
            $code = $form->get('code')->getData();
            if($code==$user->getCode()){
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/code.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function codeFront(Request $request, EntityManagerInterface $entityManager,MailerInterface $m,SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(CodeType::class, $user);
        $form->handleRequest($request);
        $session = $request->getSession();
        $user=$session->get('user')[0];
        

        if ($form->isSubmitted()) {
            $code = $form->get('code')->getData();
            if($code==$user->getCode()){
                return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/codeFront.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User();
        $form = $this->createForm(TrainerType::class, $user);
        $form->handleRequest($request);
        $user->setRole("trainer");
        $user->setPassword($this->randomPassword());
        


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $user->sendPassword($m);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new_trainer.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    

    public function register6(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User2();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $mem= new Membership();
        $type="3 months";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+3 month');
        $user->setRole("member");
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user->setMembership($mem);
       


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mem);
            $entityManager->persist($user);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('user',$user);
            $session->set('id',$user->getId());
            $date = $user->getMembership()->getExpireDate();
            $now = new \DateTime();
            $diff = date_diff($now, $date); 
            $stringdiff = $diff->format('%m months %d days'); 
            $session->set('ok',$stringdiff); 
            return $this->redirectToRoute('pay', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function register1(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User2();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $mem= new Membership();
        $type="1 year";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+1 year');
        $user->setRole("member");
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user->setMembership($mem);
       


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mem);
            $entityManager->persist($user);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('user',$user);
            $session->set('id',$user->getId());
            $date = $user->getMembership()->getExpireDate();
                $now = new \DateTime();
                $diff = date_diff($now, $date); 
                $stringdiff = $diff->format('%m months %d days'); 
                $session->set('ok',$stringdiff); 
            return $this->redirectToRoute('pay', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    public function register3(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User2();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $mem= new Membership();
        $type="3 months";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+3 month');
        $user->setRole("member");
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user->setMembership($mem);
       


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mem);
            $entityManager->persist($user);
            $entityManager->flush();
            $session = $request->getSession();
            $session->set('user',$user);
            $session->set('id',$user->getId());
            $date = $user->getMembership()->getExpireDate();
                $now = new \DateTime();
                $diff = date_diff($now, $date); 
                $stringdiff = $diff->format('%m months %d days'); 
                $session->set('ok',$stringdiff); 

            return $this->redirectToRoute('pay', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/email", name="app_user_email", methods={"GET"})
     */
    public function sendEmail(MailerInterface $mailer,User $user): Response
    {
        
        $email = (new Email())
            ->from('asma.hejaiej@esprit.tn')
            ->to('hejaiej.asma@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($user->getId())
            ->text('This is your password :');

        $mailer->send($email);
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);


        // ...
    }

    


    /**
     * @Route("/newtrainer", name="app_user_new_trainer", methods={"GET", "POST"})
     */
    public function new_trainer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(TrainerType::class, $user);
        $form->handleRequest($request);
        $user->setRole("trainer");

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new_trainer.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    
    /**
     * @Route("/gymple", name="app_front", methods={"GET", "POST"})
     */
    public function showFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $users = $entityManager
        ->getRepository(User::class)
        ->findAll();

    return $this->render('user/teams.html.twig', [
        'users' => $users,
    ]);
       
    }
    /**
     * @Route("/home", name="app_front", methods={"GET", "POST"})
     */
    public function homeFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        
     

    return $this->render('user/home.html.twig', [
        
    ]);
       
    }

    /**
     * @Route("/gymple", name="app_front", methods={"GET", "POST"})
     */
    public function homeMember(Request $request, EntityManagerInterface $entityManager): Response
    {
        
     

    return $this->render('user/homeMember.html.twig', [
        
    ]);
    
       
    }

     /**
     * @Route("/pay", name="app_front_pay", methods={"GET", "POST"})
     */
    public function pay(Request $request, EntityManagerInterface $entityManager): Response
    {
        
     

    return $this->render('user/payment.html.twig', [
        
    ]);
    
       
    }


    
    public function new_trainer2(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(TrainerType::class, $user);
        $form->handleRequest($request);
        $user->setRole("trainer");

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new_trainer.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/loginUser", name="app_user_loginFront")
     */
    public function loginFront(Request $request, EntityManagerInterface $entityManager,UserRepository $rep,SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(Login::class, $user);
        $form->handleRequest($request);
        

        if ($form->isSubmitted())
        {
          
            $ok=$rep->findByEmail($user->getEmail(),$user->getPassword());
            if ($ok!=null){
                
                
                $session = $request->getSession();
                $session->set('user',$ok);
                $session->set('id',$ok[0]->getId());
            
                $date = $ok[0]->getMembership()->getExpireDate();
                $now = new \DateTime();
                $diff = date_diff($now, $date); 
                $stringdiff = $diff->format('%m months %d days'); 
                $session->set('ok',$stringdiff);             
                 $this->addFlash(
                    'notice',
                    'Your have '.$stringdiff.' days left on your membership'
                );
            return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);}
 
        }
         return $this->render('user/loginFront.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        }


     /**
     * @Route("/login", name="app_user_login")
     */
    public function login(Request $request, EntityManagerInterface $entityManager,UserRepository $rep,SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(Login::class, $user);
        $form->handleRequest($request);
        $user->setRole("admin");
        

        if ($form->isSubmitted())
        {
          
            $ok=$rep->findByEmail($user->getEmail(),$user->getPassword());
            if ($ok!=null){
                
                
                $session = $request->getSession();
                $session->set('user',$ok);
                $session->set('id',$ok[0]->getId());
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);}
 
        }
         return $this->render('user/login.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        }

        public function loginemail(Request $request, EntityManagerInterface $entityManager,UserRepository $rep,SessionInterface $session,MailerInterface $m): Response
    {
        $user = new User();
        $form = $this->createForm(EmailType::class, $user);
        $form->handleRequest($request);
        $user->setRole("admin");


        if ($form->isSubmitted())
        {
          
            $ok=$rep->findByEmailA($user->getEmail());
            if ($ok!=null){
                
                
                $session = $request->getSession();
                $session->set('user',$ok);
                $session->set('id',$ok[0]->getId());
                $random = random_int(100000, 999999);
                $ok[0]->setCode($random);
                $ok[0]->sendCode($m);
                $entityManager->flush();
            return $this->redirectToRoute('code', [], Response::HTTP_SEE_OTHER);}
 
        }
         return $this->render('user/code.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        }


        public function loginemailFront(Request $request, EntityManagerInterface $entityManager,UserRepository $rep,SessionInterface $session,MailerInterface $m): Response
    {
        $user = new User();
        $form = $this->createForm(EmailType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted())
        {
          
            $ok=$rep->findByEmailA($user->getEmail());
            if ($ok!=null){
                
                
                $session = $request->getSession();

                $session->set('user',$ok);
                $session->set('id',$ok[0]->getId());
                $random = random_int(100000, 999999);
                $ok[0]->setCode($random);
                $ok[0]->sendCode($m);
                $entityManager->flush();
            return $this->redirectToRoute('codeFront', [], Response::HTTP_SEE_OTHER);}
 
        }
         return $this->render('user/LoginEmailFront.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        }



    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(User3::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/editMyAccount", name="app_user_editt", methods={"GET", "POST"})
     */
    public function editMember(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(editType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/modifyMember.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


     /**
     * @Route("/calendar", name="calender", methods={"GET"})
     */
    public function calendar(Request $request): Response
    {     
        return $this->render('user/calendar.html.twig');
    }





    /**
     * @Route("/{id}/editAcc", name="app_user_editAcc", methods={"GET", "POST"})
     */
    public function editAdmin(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_admin.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/block", name="app_user_block", methods={"GET", "POST"})
     */
    public function block(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
            
            $user->setBlock("y");
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        

        
    }

    /**
     * @Route("/{id}/report", name="app_user_report", methods={"GET", "POST"})
     */
    public function report(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
            $nb=$user->getReports();
            $user->setReports($nb+1);
            $entityManager->flush();

            return $this->redirectToRoute('team', [], Response::HTTP_SEE_OTHER);
        

        
    }
    

    /**
     * @Route("/{id}/unblock", name="app_user_unblock", methods={"GET", "POST"})
     */
    public function unblock(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
            
            $user->setBlock("n");
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        

        
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    
    public function apilogin2(UserRepository $rep,Request $request, NormalizerInterface $normalizer ): Response
    {
        $user=new User();
        $serializer = $this->get('serializer');
        $user= $serializer->deserialize($request->getContent(),User::class,'json');
        $ok=$rep->findByEmail($user->getEmail(),$user->getPassword());
            if ($ok!=null){
                return($this->json($ok));
            }
            return new Response("non");
    }

 public function apilogin3(UserRepository $rep,Request $request, NormalizerInterface $normalizer): Response
    {
        $user=new User();
        $user=$rep->findByEmail($request->get('email'),$request->get('password'));
        if($user!=null){
            $jsonContent=$normalizer->normalize($user,"json",["attributes"=>['role','id','email','firstName','lastName']]);
            return new Response(json_encode($jsonContent));

        }
        $response = new NewResponse();
        $response->setStatusCode(400);
        return $response;
    }
    public function apilogin(UserRepository $rep,Request $request, NormalizerInterface $normalizer ): Response
    {
        $user=new User();
        $ok=$rep->findByEmail($request->get('email'),$request->get('password'));
            if ($ok!=null){
                return new Response($this->json(["ok"=>$ok]));
            }
            return new Response(null);
    }
    public function signup1y(MailerInterface $mailer,EntityManagerInterface $entityManager,UserRepository $rep,Request $request, NormalizerInterface $normalizer ): Response
    {
        $user=new User();
        $user->setFirstName($request->get('firstname'));
        $user->setLastName($request->get('lastname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setHeight($request->get('height'));
        $user->setWeight($request->get('weight'));
        $user->setTrainingLevel('Intermediate');
        $user->setIdCard(32455678);
        $mem= new Membership();
        $type="1 year";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+1 year');
        $user->setRole("member");
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user->setMembership($mem);
        $entityManager->persist($mem);
        $entityManager->persist($user);
        $entityManager->flush();
        $user->welcome($mailer);

        return new Response("ok");
    }
    public function signup1m(MailerInterface $mailer,EntityManagerInterface $entityManager,UserRepository $rep,Request $request, NormalizerInterface $normalizer ): Response
    {
        
        $mem= new Membership();
        $type="1 month";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+1 month');
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user=new User();
        $user->setMembership($mem);
        $user->setRole("member");
        $user->setFirstName($request->get('firstname'));
        $user->setLastName($request->get('lastname'));
        $user->setEmail($request->get('email'));
        $user->welcome($mailer);
        $user->setPassword($request->get('password'));
        $user->setHeight($request->get('height'));
        $user->setWeight($request->get('weight'));
        $user->setTrainingLevel('Intermediate');
        $user->setIdCard(32455678);
        $entityManager->persist($mem);
        $entityManager->persist($user);
        $entityManager->flush();
        return($this->json($user));
        //return new Response("ok");
    }
    public function signup3m(MailerInterface $mailer,EntityManagerInterface $entityManager,UserRepository $rep,Request $request, NormalizerInterface $normalizer ): Response
    {
        $user=new User();
        $user->setFirstName($request->get('firstname'));
        $user->setLastName($request->get('lastname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setHeight($request->get('height'));
        $user->setWeight($request->get('weight'));
        $user->setRole('member');
        $user->setTrainingLevel('Intermediate');
        $user->setIdCard(32455678);
        $mem= new Membership();
        $type="3 months";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+3 month');
        $user->setRole("member");
        $mem->setType($type);
        $mem->setExpireDate($end);
        $mem->setStartDate($begin);
        $user->setMembership($mem);
        $entityManager->persist($mem);
        $entityManager->persist($user);
        $entityManager->flush();
        $user->welcome($mailer);
        return($this->json($user));
        //return new Response("ok");

    }
    public function allusers(NormalizerInterface $norm,UserRepository $rep):Response
    {
        $rep=$this->getDoctrine()->getRepository(User::class);
        $users=$rep->findAll();
        //$json=$norm->normalize($users,'json',['groups'=>'post:read']);
        return new Response($this->json(["ok"=>$users]));
    }
    public function test(EntityManagerInterface $entityManager,NormalizerInterface $Normalizer){
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $jsonContent=$Normalizer->normalize($users,"json",["attributes"=>['role','id','email','firstName','lastName']]);
        return new Response(json_encode($jsonContent));

    }
    public function update (Request $req,NormalizerInterface $norm,$id){
        $em=$this->getDoctrine()->getManager();
        $rep=$this->getDoctrine()->getRepository(User::class);
        $user=$rep->find($id);
        $user->setFirstName($req->get('firstname'));
        $user->setLastName($req->get('lastname'));
        $user->setEmail($req->get('email'));
        $em->flush();
        $jsonContent=$norm->normalize($user,"json",["attributes"=>['role','id','email','firstName','lastName']]);
        return new Response(json_encode($jsonContent));
    }
    public function del(EntityManagerInterface $em,NormalizerInterface $norm,$id){
        $rep=$this->getDoctrine()->getRepository(User::class);
        $user=$rep->find($id);
        $em->remove($user);
        $em->flush();
        return new Response("User deleted");
    }
    
    
}
