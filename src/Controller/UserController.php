<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\CodeType;
use App\Form\RegisterType;
use App\Form\AdminType;
use App\Form\EmailType;
use App\Form\Login;
use App\Form\User2;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\TrainerType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\MemberType;
use App\Controller\MailerController;
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
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,SessionInterface $session,PaginatorInterface $paginator,Request $request): Response
    {
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
   

        

        

   /* public function start_session(){
        $session = new Session(new NativeSessionStorage());
        $session->start();
        $session->set('user', $admin);
    }*/

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
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $mem= new Membership();
        $type="6 months";
        $begin =new \DateTime();
        $end =new \DateTime();
        $end->modify('+6 month');
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
            return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    public function register1(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User();
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
            return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    public function register3(Request $request, EntityManagerInterface $entityManager,MailerInterface $m): Response
    {
        $user = new User();
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
            return $this->redirectToRoute('homeMember', [], Response::HTTP_SEE_OTHER);
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
                $random = random_int(1, 10);
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
                $random = random_int(1, 10);
                $ok[0]->setCode($random);
                $ok[0]->sendCode($m);
                $entityManager->flush();
            return $this->redirectToRoute('codeFront', [], Response::HTTP_SEE_OTHER);}
 
        }
         return $this->render('user/emailFront.html.twig', [
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
        $form = $this->createForm(User2::class, $user);
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
        $form = $this->createForm(RegisterType::class, $user);
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
}
