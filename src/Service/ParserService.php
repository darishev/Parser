<?php

namespace App\Service;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class ParserService
{

    public $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function collect($url)
    {
        //Guzzle client init->
        $client = new Client();
        $resp = $client->request('get', $url)->getBody()->getContents();
        $crawler = new Crawler($resp);
        $test = $crawler->filterXPath('//*[@id="state-searchResultsV2-252189-default-1"]')->outerHtml();
        $encode = stristr($test, '{"items');
        $newencode = stristr($encode, '\'></div>', true);
        $newencode = json_decode($newencode, true);

        //Json parsing->

        $id = 16;

        $productData = [
            'productCounts' => count($newencode['items']),
            'productName' => $newencode['items'][$id]['mainState'][2]['atom']['textAtom']['text'],
            'productPrice' => $newencode['items'][$id]['mainState'][0]['atom']['price']['price'],
            'productReviews' => $newencode['items'][$id]['mainState'][3]['atom']['rating']['count'],
            'productSku' => $newencode['items'][$id]['topRightButtons'][0]['favoriteProductMolecule']['sku'],
        ];

        $this->saveProduct($productData);

    }


    private function saveProduct($productData)
    {


        $product = new Product();
        $product
            ->setName($productData['productName'])
            ->setPrice($productData['productPrice'])
            ->setSku($productData['productSku'])
            ->setSeller(null)
            ->setReviewsCount(25);

        $this->objectManager->persist($product);
        $this->objectManager->flush();

    }
}
