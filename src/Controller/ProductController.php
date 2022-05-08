<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Knp\Component\Pager\PaginatorInterface;


class ProductController extends AbstractController
{
    /* partie front */

    /**
     * @Route("/productsfront", name="app_gym_front", methods={"GET"})
     */
    public function productsfront(): Response
    {

        $product = $this->getDoctrine()->getManager()->getRepository(Products::class)->findAll();

        return $this->render('ProductFront/ProductFront.html.twig', [
            'p'=>$product
        ]);
    }


    /* partie back */
    /**
     * @Route("/", name="display_product")
     */
    public function index(PaginatorInterface $paginator,Request $request): Response
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Products::class)->findAll();
        $productPagination = $paginator->paginate(
            $product,
            $request->query->getInt('page', 1),5
        );

        return $this->render('product/displayProducts.html.twig', [
            'product' => $productPagination
        ]);


    }


    /**
     * @Route("/products", name="addprod")
     */
    public function addProduct(Request $request): Response
    {
        $product = new Products();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product); /** add lformulaire  */
            $em->flush(); /** commit fl base  */
            return $this->redirectToRoute('display_product');
        }
        return $this->render('Admin/AddProduct.html.twig',['f'=>$form->createView()]);
    }
    /**
     * @Route("/deleteprod/{IdP}", name="deleteprod")
     */
    public function deleteProduct(Products $prods): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($prods);
        $em->flush();
        return $this->redirectToRoute('display_product');
    }
    /**
     * @Route("/updateprod/{IdP}", name="updateprod")
     */
    public function updateprod(Request $request,$IdP): Response
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Products::class)->find($IdP);
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->flush(); /** commit fl base  */
            return $this->redirectToRoute('display_product');
        }
        return $this->render('Admin/updateProducts.html.twig',['f'=>$form->createView()]);
    }
    /**
     * @Route("/admin", name="display_admin")
     */
    public function indexAdmin(): Response
    {

        return $this->render('admin/displayProducts.html.twig', [

        ]);
    }
    /**
     * @Route("/products", name="display_form")
     */
    public function indexProducts(): Response
    {

        return $this->render('admin/AddProduct.html.twig', [

        ]);
    }
}
