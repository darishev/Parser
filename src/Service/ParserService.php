<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Seller;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManagerInterface;

class ParserService
{

    private $em;


    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;

    }

    public function collect($url)
    {

        //Guzzle client init->
        $client = new Client();
        $resp = $client->request('get', $url)->getBody()->getContents();
        $crawler = new Crawler($resp);
        $domData = $crawler->filterXPath('//*[@id="state-searchResultsV2-252189-default-1"]')->outerHtml();
        $domData = str_replace(['\'></div>', '<div id="state-searchResultsV2-252189-default-1" data-state=\''], '', $domData);
        $jsonFormat = json_decode($domData, true);

        foreach ($jsonFormat['items'] as $itemData) {

            if (isset($itemData['mainState'][2]['atom']['textAtom']['text'])) {

                $productData = [
                    'productCounts' => count($itemData),
                    'productName' => $itemData['mainState'][2]['atom']['textAtom']['text'],
                    'productPrice' => str_replace([' ', '₽'], '', $itemData['mainState'][0]['atom']['price']['price']),
                    'productSku' => $itemData['topRightButtons'][0]['favoriteProductMolecule']['sku'],
                    'productReviews' => $this->reviewCheck($itemData)
                ];

                $this->saveProduct($productData);

            }

        }

    }

    public function reviewCheck($itemReview): ?int
    {

        if (isset($itemReview['mainState'][3]['atom']['rating']['count'])) {
            $inputItem = $itemReview['mainState'][3]['atom']['rating']['count'];
            return (int)str_replace(' отзыва', '', $inputItem);
        } else {
            return 0;
        }

    }

    private function saveProduct($productData)
    {
        $entityManager = $this->em;
        $product = new Product();
        $id = 3;

        $sellerCheck = $entityManager->getRepository(Seller::class)->find($id);
        // $sellerCheck = $entityManager->getRepository(Seller::class)->findBy(array('name' => 'Reebok'));

        $product
            ->setName($productData['productName'])
            ->setPrice($productData['productPrice'])
            ->setSku($productData['productSku'])
            ->setSeller($sellerCheck)
            ->setReviewsCount($productData['productReviews'])
            ->setCreatedAtValue();

        $entityManager->persist($product);
        $entityManager->flush();

    }
}
