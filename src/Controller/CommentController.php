<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Quack;
use App\Form\CommentType;
use App\Repository\QuackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    #[Route('quacks/comment/{id}', name: 'app_comment')]
    public function index(int $id,Request $request,Quack $quack, EntityManagerInterface $em, QuackRepository $quackRepository): Response
    {
        $user = $this->getUser();

        $quack = $quackRepository->findAllCommentQuack($id);

        if ($user) {
            $comment = new Comment();
            $formComment = $this->createForm(CommentType::class, $comment);
            $formComment->handleRequest($request);
            if ($formComment->isSubmitted() && $formComment->isValid()) {
                $comment->setRating(0)
                    ->setAuthor($user)
                    ->setQuack($quack);

                $quack->setNbresponse($quack->getNbresponse() + 1);

                $em->persist($comment);
                $em->flush();

                return $this->redirect($request->getUri());
            }
        }

        return $this->render('comment/index.html.twig', [
            'form' => $formComment->createView(),
            'quack' => $quack,

        ]);
    }
}
