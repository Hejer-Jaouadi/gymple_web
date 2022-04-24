<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use App\Repository\CommandsRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commands;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(SessionInterface $session , ProductsRepository $productRepository): Response
    {
        $panier = $session->get('panier',[]);

        $panierWithData = [];
        foreach($panier as $id => $quantity){
        $panierWithData[]= [
            'product'=> $productRepository->find($id),
            'quantity'=>$quantity
        ];

        }
            $total = 0 ;
        foreach($panierWithData as $item){
            $totalItem = $item['product']->getPrice()* $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/PanierDisplay.twig', [
            'items'=>$panierWithData,
            'total'=> $total
        ]);
    }

    /**
 * @Route("/commands", name="command_index")
 */
    public function commandDisplay(SessionInterface $session , CommandsRepository $commandsRepository): Response
    {
        $command = $this->getDoctrine()->getManager()->getRepository(commands::class)->findAll();

        return $this->render('cart/commandDisplay.html.twig', [
            'items'=>$command,

        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session){


        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }


        $session->set('panier',$panier);

        return $this->redirectToRoute('cart_index');

    }
    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);

        return $this->redirectToRoute("cart_index");

    }
    /**
     * @Route("/panier/updatequantity/{id}", name="cart_update_quantity")
     */
    public function updateQuantity($id, SessionInterface $session){

        $panier = $session->get('panier',[]);
        $panier[$id]=$panier[$id]+1;

        $session->set('panier',$panier);

        return $this->redirectToRoute("cart_index");

    }
    /**
     * @Route("/panier/updatequantity2/{id}", name="cart_update_quantity2")
     */
    public function updateQuantity2($id, SessionInterface $session){

        $panier = $session->get('panier',[]);
        $panier[$id]=$panier[$id]-1;

        $session->set('panier',$panier);

        return $this->redirectToRoute("cart_index");

    }


    //partie stripe

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(SessionInterface $session, ProductsRepository $productRepository,Request $request): Response
    {
        $panier = $session->get('panier',[]);

        $panierWithData = [];
        foreach($panier as $id => $quantity){
            $panierWithData[]= [
                'product'=> $productRepository->find($id),
                'quantity'=>$quantity
            ];
            $com = $productRepository->find($id);
            $command = new commands();
            $command->setName($com->getName());
            $command->setCategory($com->getCategory());
            $command->setDescription($com->getDescription());
            $command->setPrice($com->getPrice());
            $command->setQuantity(1);

        }
        $total = 0 ;
        foreach($panierWithData as $item){
            $totalItem = $item['product']->getPrice()* $item['quantity'];
            $total += $totalItem;
        }


            $em = $this->getDoctrine()->getManager();
            $em->persist($command); /** add lformulaire  */
            $em->flush(); /** commit fl base  */




        \Stripe\Stripe::setApiKey('sk_test_51K9oMoFGG3TCydWDIAAn0Ewahl2peotVjBwuq6aaox3rDW2Bqs1ned3JHuCwRqicCtI4pgOEp1HwQQ4tYtqAClul00dXq3fpiH');
        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Gym Product',
                    ],
                    'unit_amount' => $total*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success_url',
            'cancel_url' => 'https://example.com/cancel',
        ]);
        return $this->redirect($session->url,303);
    }
    /**
     * @Route("/success_url", name="success_url")
     */
    public function successUrl(): Response
    {
        return $this->render('ProductFront/success.html.twig');
    }
    /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('ProductFront/cancel.html.twig');
    }






}
