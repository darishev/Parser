<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp;
use function MongoDB\BSON\toRelaxedExtendedJSON;

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

        $url = implode($url);
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $url);

        if ($res->getBody()) {
            dd( $res->GetHeaders());
            // JSON string: { ... }
        }

    }
}
