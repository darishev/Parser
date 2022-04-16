<?php

namespace App\Controller;

use App\Controller\Admin\DashboardController;
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

    public function collectData($url)
    {

        $url = implode($url);

        if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) && str_contains($url,'https://www.ozon.ru/')) {
            $parserService = new ParserService($this->em);
            return $parserService->collect($url);
        } else {
        $this->addFlash(
                'warning',
                'Пожалуйста проверьте URL, ссылка некорретна! '
            );
            return $this->redirectToRoute('parser');
        }

    }
}




