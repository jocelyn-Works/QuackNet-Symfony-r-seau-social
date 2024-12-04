<?php

namespace App\Controller;

use App\Form\DuckType;
use App\Service\UploaderPicture;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class DuckController extends AbstractController
{
    #[Route('/duck/edit', name: 'app_duck_update')]
    public function index(Request $request, UploaderPicture $uploaderPicture, EntityManagerInterface $em): Response
    {
        $duck = $this->getUser();

        $form = $this->createForm(DuckType::class, $duck, ['new_duck' => false]);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();
            if ($picture) {
                $duck->setPicture(($uploaderPicture->uploadProfilImage($picture,$duck->getPicture())));
            }

            $em->flush();


            return $this->redirectToRoute('app_quack-all');

        }
        return $this->render('duck/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/duck/password', name: 'update_duck_password')]
    public function new(Request $request,  UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $duck = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('newPassword' , RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrez deux mot de passe Identique']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Veuillez entrez plus de 5 catactéres'])
                ],
                'invalid_message' => 'Les champs des mots de passe doivent correspondre.',
                'required' => false,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            if ($newPassword){
                $hash = $passwordHasher->hashPassword($duck, $newPassword);
                $duck->setPassword($hash);
            }
            $em->flush();

            return $this->redirectToRoute('app_quack-all');
        }
        return $this->render('duck/password.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
