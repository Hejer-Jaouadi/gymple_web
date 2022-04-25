<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {

        $client = new HttpClient;
        $request = new Request();
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://bodybuilding-quotes1.p.rapidapi.com/random-quote',['headers'=>['X-RapidAPI-Host' => 'bodybuilding-quotes1.p.rapidapi.com',
        'X-RapidAPI-Key' => '4c0a99687fmshff00f8e0368243bp104588jsn8d9d5bfef934']]);
        
       
       
        
        
        $data = json_decode($response->getContent(), true);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data'=>$data
        ]);
    }
}
