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
        $client = new Client(array('connect_timeout' => 10));
        $resp = $client->request('GET', $url)->getBody()->getContents();

        $crawler = new Crawler($resp);

        //5 attempts for connect and extract needled json file
        for ($i = 0; $i < 5; $i++) {
            if ($domData = $crawler->filterXPath('//*[@id="state-searchResultsV2-252189-default-1"]')->outerHtml()) {
                $domData = str_replace(['\'></div>', '<div id="state-searchResultsV2-252189-default-1" data-state=\''], '', $domData);
                $jsonFormat = json_decode($domData, true);
            }

        }
        //json extracted

        //productArrayGeneration
        foreach ($jsonFormat['items'] as $itemData) {

            if (isset($itemData['mainState'][2]['atom']['textAtom']['text'])) {

                $productData = [
                    'productCounts' => count($itemData),
                    'productName' => $itemData['mainState'][2]['atom']['textAtom']['text'],
                    'productPrice' => str_replace([' ', '₽'], '', $itemData['mainState'][0]['atom']['price']['price']),
                    'productSku' => $itemData['topRightButtons'][0]['favoriteProductMolecule']['sku'],
                    'productSeller' => $this->sellerCheck($itemData), //Check duplication
                    'ProductUpdated' => '',
                    'productReviews' => $this->reviewsCountCheck($itemData)//Extract int value
                ];

                $this->dublicateCheck($productData);
            }
        }

    }

    public function sellerCheck($sellerCheck)
    {
        $seller = new Seller();
        $sellerName = strip_tags(strstr($sellerCheck['multiButton']['ozonSubtitle']['textAtomWithIcon']['text'], 'продавец '));
        if (!$this->em->getRepository(Seller::class)->findBy(array('name' => $sellerName))) {
            $seller->setName($sellerName);
            $this->em->persist($seller);
            $this->em->flush();
            return $seller->setName($sellerName);
        } else {
            //getID
            $idCheck = $this->em->getRepository(Seller::class)->findBy(array('name' => $sellerName));
            return $idCheck['0'];
        }

    }

    public function reviewsCountCheck($itemReview): ?int
    {

        if (isset($itemReview['mainState'][3]['atom']['rating']['count'])) {
            $inputItem = $itemReview['mainState'][3]['atom']['rating']['count'];
            return (int)str_replace(' отзыва', '', $inputItem);
        } else {
            return 0;
        }

    }

    public function dublicateCheck($data)
    {
        if (!$this->em->getRepository(Product::class)->findBy(array('sku' => $data['productSku']))) {
            $this->saveProduct($data);
        }

    }

    private function saveProduct($productData, bool $upd = false)
    {
        $entityManager = $this->em;
        $product = new Product();
        $product
            ->setName($productData['productName'])
            ->setPrice($productData['productPrice'])
            ->setSku($productData['productSku'])
            ->setSeller($productData['productSeller'])
            ->setReviewsCount($productData['productReviews'])
            ->setCreatedAtValue();

        $entityManager->persist($product);
        $entityManager->flush();

    }
}
