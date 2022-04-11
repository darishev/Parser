<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Seller;
use App\Form\ParsingRequestFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\ParserController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DashboardController extends AbstractDashboardController
{
    #[Route('/parser', name: 'admin')]
    public function show(Request $request, ParserController $parsing): Response
    {
        $form = $this->createForm(ParsingRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $result = $parsing->collectData($form->getData());
            dd($result);
        }


        return $this->render('admin/index.html.twig', [
            'form' => $form->createView()
        ]);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
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
