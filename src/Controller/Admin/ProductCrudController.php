<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }


    public function configureFields(string $pageName): iterable
    {


        yield AssociationField::new('seller', 'name');
        yield TextField::new('name');
        yield TextField::new('sku');
        yield IntegerField::new('reviews_count');
        yield DateTimeField::new('created_date');
        yield TextField::new('price')
            ->hideOnIndex();
    }

}
