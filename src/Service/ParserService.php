<?php

namespace App\Service;

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


class ParserService
{


    public function collect($url)
    {

        $client = new Client();
        $resp = $client->request('get', $url)->getBody()->getContents();
        $crawler = new Crawler($resp);
        $test = $crawler->filterXPath('//*[@id="state-searchResultsV2-252189-default-1"]')->outerHtml();
        $encode = stristr($test, '{"items');
        $newencode = stristr($encode, '\'></div>', true);
        $newencode = json_decode($newencode, true);

        return ($newencode['items'][1]['mainState'][0]['atom']['price']['price']);















    }
}
