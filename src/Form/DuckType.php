<?php

namespace App\Form;

use App\Entity\Duck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class DuckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            
            ->add('firstname',TextType::class,[
                'label' => 'Prénom :'
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom :'
                ])
            ->add('email',EmailType::class,[
                'label' => 'Email :'
            ])    
            ->add('duckname', TextType::class,[
                'label' => 'nom de canard :'
            ])
            ->add('password',  PasswordType::class,[
                'label' => 'Mot de Passe :',
               
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Duck::class,
        ]);
    }
}
