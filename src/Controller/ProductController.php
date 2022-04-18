<?php

namespace App\Controller;

use App\Controller\Admin\DashboardController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class ProductController extends DashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/products', name: 'app_productList')]
    public function productListView(): Response
    {
        $productIdFind = $this->entityManager
            ->getRepository(Product::class)
            ->findAll();


        return $this->render('product/productList.html.twig', [
            'allProducts' => $productIdFind
        ]);
    }

    #[Route('/products/{id}', name: 'app_product')]
    public function product($id): Response
    {
        $productIdFind = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => $id]);


        return $this->render('product/index.html.twig', [
            'productId' => $productIdFind->getId(),
            'productName' => $productIdFind->getName(),
            'productPrice' => $productIdFind->getPrice(),
            'productSku' => $productIdFind->getSku(),
            'productCreatedAt' => $productIdFind->getCreatedDate()->format('Y-m-d'),
            //'productUpdated' => $productIdFind->getUpdatedDate()->format('Y-m-d'),
            'Seller' => $productIdFind->getSeller(),

        ]);
    }
}
