<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Quack;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('quacks/like/{id}/{score}', name: 'quack_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function rateQuack(Quack $quack,Request $request, LikeRepository $likeRepository, int $score, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();


        if ($currentUser !== $quack->getAuthor()){
            $like = $likeRepository->findOneBy(['quack' => $quack, 'author' => $currentUser]) ?? null;

            if ($like){

                if(($like->isIsLiked() && $score > 0 || (!$like->isIsLiked() && $score < 0))){
                    $em->remove($like);
                    $quack->setRating($quack->getRating() + ($score > 0 ? -1 : 1));
                }else{
                    $like->setIsLiked(!$like->isIsLiked());
                    $quack->setRating($quack->getRating() + ($score > 0 ? 2 : -2));
                }
            }else{

                $like = new Like();
                $like->setQuack($quack)
                    ->setAuthor($currentUser)
                    ->setIsLiked($score > 0 ? true : false);

                $em->persist($like);
                $quack->setRating($quack->getRating() + $score);
            }

            $em->flush();
        }

        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirect(('app_quack-all'));
    }

    #[Route('comment/like/{id}/{score}', name: 'comment_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function rateCommentQuack(Comment $comment,Request $request, LikeRepository $likeRepository, int $score, EntityManagerInterface $em): Response
    {
        $currentUser = $this->getUser();


        if ($currentUser !== $comment->getAuthor()){
            $like = $likeRepository->findOneBy(['comment' => $comment, 'author' => $currentUser]) ?? null;

            if ($like){

                if(($like->isIsLiked() && $score > 0 || (!$like->isIsLiked() && $score < 0))){
                    $em->remove($like);
                    $comment->setRating($comment->getRating() + ($score > 0 ? -1 : 1));

                }else{
                    $like->setIsLiked(!$like->isIsLiked());
                    $comment->setRating($comment->getRating() + ($score > 0 ? 2 : -2));
                }
            }else{

                $like = new Like();
                $like->setComment($comment)
                    ->setAuthor($currentUser)
                    ->setIsLiked($score > 0 ? true : false);

                $em->persist($like);
                $comment->setRating($comment->getRating() + $score);
            }

            $em->flush();
        }

        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirect(('app_quack-all'));
    }
}
