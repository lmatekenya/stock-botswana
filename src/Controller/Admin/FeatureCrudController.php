<?php
// src/Controller/Admin/FeatureCrudController.php

namespace App\Controller\Admin;

use App\Entity\Feature;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class FeatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Feature::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Feature')
            ->setEntityLabelInPlural('Features')
            ->setSearchFields(['title', 'description'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('title')
            ->setRequired(true);
        yield TextField::new('icon')
            ->setHelp('Icon class or name (e.g., fa fa-star, icon-heart)')
            ->setRequired(true);
        yield TextareaField::new('description')
            ->setRequired(true);

        yield DateTimeField::new('createdAt')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->onlyOnIndex();
        yield DateTimeField::new('updatedAt')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Add Feature');
            });
    }
}
