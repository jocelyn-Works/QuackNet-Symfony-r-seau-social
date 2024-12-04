<?php

namespace App\Controller\Admin;

use App\Entity\Duck;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DuckCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Duck::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs') //  nom de la table a afficher
            ->setEntityLabelInSingular('Ajouter un Utilisateur') // crée un utilisateur
            ->setPageTitle("index", "  Utilisateurs") // titre page
            ->setPaginatorPageSize(10); // 10 utilisateurs
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),

            TextField::new('firstname')
                ->setLabel('Prénom'),

            TextField::new('lastname')
                ->setLabel('Nom'),

            TextField::new('duckname')
                ->setLabel('Nom de canard'),

            EmailField::new('email')
                ->setLabel('Adress Email'),
            // ->setFormTypeOption('disabled', 'disabled')

            ImageField::new('picture')
                ->setLabel('Image de Profil')
                ->hideOnForm(),



            ArrayField::new('roles')
                ->hideOnIndex(),

            TextField::new('password')
                ->hideOnIndex()
                ->hideOnForm(),






        ];
    }
}
