<?php
// src/Controller/Admin/UserCrudController.php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['fullName', 'email'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('fullName')
            ->setRequired(true);
        yield EmailField::new('email')
            ->setRequired(true);

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            yield PasswordField::new('password')
                ->setFormType(PasswordType::class)
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->setHelp($pageName === Crud::PAGE_EDIT ? 'Leave empty to keep current password' : '');
        }

        yield ChoiceField::new('userType')
            ->setChoices([
                'Buyer' => 'buyer',
                'Seller' => 'seller'
            ])
            ->setRequired(true);

        yield ChoiceField::new('module')
            ->setChoices([
                'Local Farming' => 'localFarming',
                'Building & Housing' => 'buildingHousing',
                'Healthcare' => 'healthcare',
                'Car Sales' => 'carSales',
                'Food Services' => 'foodServices'
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
                return $action->setIcon('fa fa-plus')->setLabel('Add User');
            });
    }
}
