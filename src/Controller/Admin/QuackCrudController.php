<?php

namespace App\Controller\Admin;

use App\Entity\Quack;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuackCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Quack::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Quacks') //  nom de la table a afficher

            ->setEntityLabelInSingular('Ajouter un Quack')       // crée un produit

            ->setPageTitle("index", "Quack") // titre page

            ->setPaginatorPageSize(10); // 10 utilisateurs
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setLabel('ID')
                ->hideOnForm(),


            TextField::new('content')
                ->setLabel('Contenu')
                ->setRequired(true) // Champ obligatoire
                ->setHelp('Ce champ contient le texte principal du Quack. Il doit être d\'au moins 5 caractères.'),

            // Le champ "picture" pour l'image de profil, masqué dans le formulaire
            ImageField::new('picture')
                ->setLabel('Image de Profil')

                ->hideOnForm(),


            // Le champ "created_at" qui est une date, masqué dans le formulaire
            DateTimeField::new('created_at')
                ->setLabel('Date de Création')
                ->setFormat('dd/MM/yyyy')
                ->hideOnForm(),

            // Le champ "author", qui est une relation ManyToOne avec l'entité Duck
            AssociationField::new('author')
                ->setLabel('Auteur')
                ->setFormTypeOption('disabled', true) // Option pour rendre ce champ en lecture seule
                ->hideOnIndex(), // Masquer sur l'index si nécessaire


            // Le champ "nbresponse" pour le nombre de réponses
            IntegerField::new('nbresponse')
                ->setLabel('Nombre de Réponses')
                ->setRequired(false),

            // Le champ "likes", une relation OneToMany avec Like
            AssociationField::new('likes')
                ->setLabel('Likes')
                ->hideOnForm(),

            // Le champ "comments", une relation OneToMany avec Comment
            AssociationField::new('comments')
                ->setLabel('Commentaires')
                ->hideOnForm(),

            // Le champ "active", un champ booléen pour savoir si le Quack est actif ou non
            BooleanField::new('active')
                ->setLabel('Actif')
                ->setHelp('Indique si le Quack est actif ou non.'),
        ];
    }
}
