<?php

namespace App\Controller;

use App\Controller\Admin\DashboardController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class ProductController extends DashboardController
{
    #[Route('/product/{id}', name: 'app_product')]
    public function entityResponse(Request $request): Response
    {
        $entityID = $request->query->get('id');



        return $this->render('product/index.html.twig', [
            'controller_name' =>$entityID,
        ]);
    }
}
