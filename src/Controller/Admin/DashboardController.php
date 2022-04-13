<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Seller;
use App\Form\ParsingRequestFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\ParserController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DomCrawler\Crawler;
use Twig\Environment;

class DashboardController extends AbstractDashboardController
{
    #[Route('/parser', name: 'admin')]
    public function show(Request $request, ParserController $parsing): Response
    {

        $form = $this->createFormBuilder(null)
            ->add('query', TextType::class)
            ->add('search', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $parsing->collectData($form->getData());

        }
        return $this->render('admin/index.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Parser project JCAT');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Parser', 'fa fa-paste');
        // yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        //    yield MenuItem::linkToCrud('Sellers', 'fas fa-map-marker-alt', Seller::class);
        // yield MenuItem::linkToCrud('Products', 'fas fa-comments', Product::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
