<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Seller;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\DashboardController;

class ParserService
{

    private $em;


    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;

    }

    public function collect($url): void
    {
        //Guzzle client init->
        $client = new Client();
        $resp = $client->request('GET', $url, array('connect_timeout' => 0))->getBody()->getContents();

        //DOM_Crawler init->
        $crawler = new Crawler($resp);

        //Json extract
        $domData = $crawler->filterXPath("//*[contains(concat(' ', @id, ' '), 'state-searchResultsV2')]")->outerHtml();
        $domData = str_replace(['\'></div>', '<div id="state-searchResultsV2', '-252189-default-1" ', '-1304093-default-1" ', 'data-state=\''], '', $domData);
        $jsonFormat = json_decode($domData, true);

        //productData generation array
        foreach ($jsonFormat['items'] as $itemData) {
            if (isset($itemData['mainState'][2]['atom']['textAtom']['text']) && count($itemData) != 8) {

                $productData = [
                    'productName' => $itemData['mainState'][2]['atom']['textAtom']['text'],
                    'productPrice' => str_replace([' ', '₽'], '', $itemData['mainState'][0]['atom']['price']['price']),
                    'productSku' => $itemData['topRightButtons'][0]['favoriteProductMolecule']['sku'],
                    'productSeller' => $this->requireSellerUpdateCheck($itemData), //Check exist seller or no
                    'productReviews' => $this->reviewsCountCheck($itemData)//Extract integer from string reviews
                ];

                $this->requireUpdateCheck($productData);
            }
        }
    }

    public function requireSellerUpdateCheck($productData): ?object
    {
        $seller = new Seller();
        $sellerName = strip_tags(strstr($productData['multiButton']['ozonSubtitle']['textAtomWithIcon']['text'], 'продавец '));
        $sellerName = str_replace('продавец ', '', $sellerName);

        $idCheck = $this->em->getRepository(Seller::class)->findOneBy(array('name' => $sellerName));
        if (!$idCheck) {
            $seller->setName($sellerName);
            $this->em->persist($seller);
            $this->em->flush();
            //if seller doesn't exist in database, create new
            return $seller->setName($sellerName);
        } else
            //if seller_id exist in current product, return original input seller_id for product
            return $idCheck;
    }


    public function reviewsCountCheck($itemReview): ?int
    {
        $itemReview = $itemReview['mainState'][3]['atom']['rating']['count'];

        if ($itemReview !== null)
            return (int)str_replace(' отзыв%', null, $itemReview);
        else
            return 0;
    }

    public function requireUpdateCheck($productData): void
    {
        $searchSku = $this->em->getRepository(Product::class)->findOneBy(array('sku' => $productData['productSku']));

        if ($searchSku) {
            $searchSku->setUpdatedValues();
            $this->em->persist($searchSku);
            //if in database exist productData[sku], update -> UpdatedValues
            $this->em->flush();
        } else {
            //if in database doesn't exist productData[sku], return original product array
            $this->saveProduct($productData);
        }
    }

    private function saveProduct($productData): void
    {
        $product = new Product();
        $product
            ->setName($productData['productName'])
            ->setPrice($productData['productPrice'])
            ->setSku($productData['productSku'])
            ->setSeller($productData['productSeller'])
            ->setReviewsCount($productData['productReviews'])
            ->setCreatedAtValue();

        $this->em->persist($product);
        $this->em->flush();
    }

}

