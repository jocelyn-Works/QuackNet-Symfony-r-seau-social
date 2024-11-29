<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class SecurityController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(private $formLoginAuthenticator, EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/', name: 'signup')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator): Response
    {
        $duck = new Duck();

        $duckForm = $this->createForm(DuckType::class, $duck);
        $duckForm->handleRequest( $request);

        if ($duckForm->isSubmitted() && $duckForm->isValid()) {
            $hash = $passwordHasher->hashPassword($duck, $duck->getPassword());
            $duck->setPassword($hash);

            $this->em->persist($duck);
            $this->em->flush();

            return $userAuthenticator->authenticateUser($duck, $this->formLoginAuthenticator, $request);
        }

        return $this->render('security/signup.html.twig', [
             'signupForm' => $duckForm->createView(),
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {           // connexion

        // erreur dauthentification
        $error = $authenticationUtils->getLastAuthenticationError();
        // récupère le nom entré par l'utilisateur lors de la derniére connxion
        $username = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}
