<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function index(): Response
    {
        $client = new HttpClient;
$request = new Request;
$client = HttpClient::create();
$response = $client->request('GET', 'https://live-fitness-and-health-news.p.rapidapi.com/news',['headers'=>['X-RapidAPI-Host' => 'live-fitness-and-health-news.p.rapidapi.com',
'X-RapidAPI-Key' => '4c0a99687fmshff00f8e0368243bp104588jsn8d9d5bfef934']]);

$statusCode = $response->getStatusCode();
// $statusCode = 200
$contentType = $response->getHeaders()['content-type'][0];
// $contentType = 'application/json'
$content = $response->getContent();
// $content = '{"id":521583, "name":"symfony-docs", ...}'


$data = json_decode($response->getContent(), true);

//echo $content;
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'contents'=>$data
        ]);
    }
}
