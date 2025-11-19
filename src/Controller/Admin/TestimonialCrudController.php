<?php
// src/Controller/Admin/TestimonialCrudController.php

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class TestimonialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Testimonial')
            ->setEntityLabelInPlural('Testimonials')
            ->setSearchFields(['author', 'text', 'role'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextareaField::new('text')
            ->setRequired(true);
        yield TextField::new('author')
            ->setRequired(true);
        yield TextField::new('role')
            ->setRequired(true);
        yield TextField::new('avatar')
            ->setHelp('Avatar URL or identifier')
            ->setRequired(true);
        yield ChoiceField::new('avatarType')
            ->setChoices([
                'Farmer' => 'farmer',
                'Builder' => 'builder',
                'Health' => 'health'
            ])
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
                return $action->setIcon('fa fa-plus')->setLabel('Add Testimonial');
            });
    }
}
