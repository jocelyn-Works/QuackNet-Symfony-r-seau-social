<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use App\Service\UploaderQuackPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quacks')]
class QuackController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    #[Route(name: 'app_quack-all')]
    public function index(QuackRepository $quackRepository,
                          Request $request, EntityManagerInterface $entityManager,
                          UploaderQuackPicture $uploaderQuackPicture): Response
    {
        $currentUser = $this->getUser();

        $newQuack = new Quack();
        $form = $this->createForm(QuackType::class, $newQuack);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();

            if ($picture) {
                $uploadedPicturePath = $uploaderQuackPicture->uploadQuackImage($picture);
                $newQuack->setPicture($uploadedPicturePath);
            }

            $newQuack->setAuthor($currentUser);


            $entityManager->persist($newQuack);
            $entityManager->flush();

            return $this->redirect($request->getUri());


        }
        $allQuacks = $quackRepository->findAllQuacks();

        return $this->render('quack/index.html.twig', [
            'quacks' => $allQuacks,
            'quack' => $newQuack,
            'quackForm' => $form,
        ]);
    }





    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    #[Route('/{id}/edit', name: 'app_quack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, EntityManagerInterface $entityManager,UploaderQuackPicture $uploaderQuackPicture): Response
    {
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $uploadedPicturePath = $uploaderQuackPicture->uploadQuackImage($picture);
                $quack->setPicture($uploadedPicturePath);

            }
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    #[Route('/{id}', name: 'app_quack_delete')]
    public function delete(int $id,Request $request, QuackRepository $quackRepository, EntityManagerInterface $entityManager): Response
    {
      $quack = $quackRepository->find($id);

      if ($quack) {
          $entityManager->remove($quack);
          $entityManager->flush();
      }

        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirect(('post'));
    }
}
