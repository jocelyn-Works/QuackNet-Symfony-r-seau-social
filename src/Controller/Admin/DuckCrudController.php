<?php

namespace App\Controller\Admin;

use App\Entity\Duck;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class DuckCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) {
        $this->hasher = $hasher;
    }
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
        if (Crud::PAGE_NEW === $pageName) {
            return [
                TextField::new('firstname')
                    ->setLabel('Prénom'),

                TextField::new('lastname')
                    ->setLabel('Nom'),

                TextField::new('duckname')
                    ->setLabel('Nom de canard'),

                EmailField::new('email')
                    ->setLabel('Adress Email'),

                Field::new('password', 'Password')
                    ->setFormType(RepeatedType::class)
                    ->setFormTypeOptions([
                        'type' => PasswordType::class,
                        'first_options' => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat password'],
                        'error_bubbling' => true,
                        'invalid_message' => 'The password fields do not match.',
                    ]),

                ChoiceField::new('roles', 'Rôles')
                    ->setChoices([
                        'Utilisateur' => 'ROLE_USER',
                        'Modérateur' => 'ROLE_MANAGER',
                        'Administrateur' => 'ROLE_ADMIN',
                    ])
                    ->allowMultipleChoices(true)
                    ->renderExpanded(false),

            ];
        }
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

            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Modérateur' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(true)
                ->renderExpanded(false),


        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Duck) {
            $plainPassword = $entityInstance->getPassword();
            if ($plainPassword) {
                $hashedPassword = $this->hasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
