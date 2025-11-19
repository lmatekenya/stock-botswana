<?php
// src/Controller/Admin/SectorCrudController.php

namespace App\Controller\Admin;

use App\Entity\Sector;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class SectorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sector::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sector')
            ->setEntityLabelInPlural('Sectors')
            ->setSearchFields(['name', 'description'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name')
            ->setRequired(true);
        yield TextField::new('icon')
            ->setHelp('Icon class or name')
            ->setRequired(true);
        yield ChoiceField::new('status')
            ->setChoices([
                'Live' => 'live',
                'Coming Soon' => 'coming'
            ])
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
                return $action->setIcon('fa fa-plus')->setLabel('Add Sector');
            });
    }
}
