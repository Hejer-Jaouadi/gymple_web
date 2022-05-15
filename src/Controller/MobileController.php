<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class MobileController extends AbstractController
{
    /**
     * @Route("/mobile/addproduct", name="app_add")
     */
    public function addprod(Request $request, NormalizerInterface $Normalizer)
    {
        $prod = new Products();
        $name = $request->get("name");
        $idp = $request->get("IdP");
        $desc = $request->get("description");
        $category = $request->get("category");
        $quantity = $request->get("quantity");
        $price = $request->get("price");

        $em = $this->getDoctrine()->getManager();

        $prod->setIdP($idp);
        $prod->setQuantity($quantity);
        $prod->setName($name);
        $prod->setDescription($desc);
        $prod->setCategory($category);
        $prod->setPrice($price);

        $em->persist($prod);
        $em->flush();
        $jsonContent = $Normalizer->normalize($prod,'json',['groups'=>'post:read']);



        return new JsonResponse($jsonContent);
    }
    /**
     * @Route("/mobile/displayprod", name="app_mobile")

     */
    public function allprod(){
    $prod = $this->getDoctrine()->getManager()->getRepository(Products::class)->findAll();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize($prod);

    return new JsonResponse($formatted);
    }

    /**
     * @Route("/mobile/deleteprod", name="app_delete")

     */
    public function delprod(Request $request){

        $id = $request->get("IdP");
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Products::class)->find($id);
        $em->remove($prod);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($prod);

        return new JsonResponse($formatted);
    }
    /**
     * @Route("/mobile/updateprod", name="app_update")

     */
    public function updateproduct(Request $request){

        $em = $this->getDoctrine()->getManager();
        $prod = $this->getDoctrine()->getManager()->getRepository(Products::class)->find($request->get("IdP"));
        $prod->setIdP($request->get("IdP"));
        $prod->setName($request->get("name"));
        $prod->setDescription($request->get("description"));
        $prod->setCategory($request->get("category"));
        $prod->setQuantity($request->get("quantity"));
        $prod->setPrice($request->get("price"));

        $em->persist($prod);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($prod);

        return new JsonResponse($formatted);
    }

}
