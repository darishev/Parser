<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ParserService;


class ParserController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;

    }

    public function index(Request $request): Response
    {
        return $this->render('parser/index.html.twig', [
            'controller_name' => 'ParserController',
        ]);
    }

    public function collectData($url)
    {

        $parserService = new ParserService($this->em);
        $parserService->collect(implode($url));


    }
}




