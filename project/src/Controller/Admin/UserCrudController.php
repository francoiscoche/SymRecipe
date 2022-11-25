<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs') // Pour renommer les labels
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index', 'SymRecipe - Administration des utilisateurs')
            ->setPaginatorPageSize(10);
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {

        // Configure les champs visible sur le tableau ou dans le formulaire du backoffice
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('fullname'),
            TextField::new('pseudo'),
            TextField::new('password')->hideOnIndex(),
            // TextField::new('email')->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email'),
            ArrayField::new('roles'),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
    
}
