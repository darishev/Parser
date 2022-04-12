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

class ParserController extends AbstractController
{
    public function index(Request $request): Response
    {
        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController',
        ]);
    }

    public function collectData($url)
    {

        $client = new Client(); //sync client
        $proCli = $client->getAsync('https://www.ozon.ru/category/kedy-i-slipony-muzhskie-7660/?sorting=new')->wait();
        $crawler = new Crawler($proCli->getBody()->getContents());
      //  $xpatch = '';
      //  dd($crawler->filterXPath($xpatch)->outerHtml());
        dd($crawler->outerHtml());



    }
}




