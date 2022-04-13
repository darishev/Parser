<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Handler\CurlMultiHandler;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\HandlerStack;
use App\Service\ParserService;


class ParserController extends AbstractController
{
    public function index(Request $request): Response
    {
        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController',
        ]);
    }

    public function collectData(Request $url)
    {

        $parserService = new ParserService();
        $parserService->collect($url);


    }
}




