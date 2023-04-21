<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\Score;
use App\Form\GameType;
use App\Form\LevelType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Repository\ColorRepository;
use App\Repository\ScoreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    #[Route('/game', name: 'user_game')]
    public function selectUser(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('game/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}', name: 'app_game')]
    public function index(User $user, ColorRepository $colorRepository, GameRepository $gameRepository): Response
    {
        $colors = $colorRepository->findAll();
        shuffle($colors);

        $game = new Game();
        $game->setSquence($this->getSequence($colors));
        $game->setUser($user);
        $gameRepository->save($game, true);


        return $this->render('game/index.html.twig', [
            'colors' => $colors,
            'gameId' => $game->getId(),
        ]);
    }

    #[Route('/play/{id}', name: 'play_game')]
    public function responseChoice(Request $request, Game $game, GameRepository $gameRepository, ScoreRepository $scoreRepository): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($game->getProposal() === $game->getSquence()) {
                
                $score = $game->getUser()->getScore() !== null ? $game->getUser()->getScore() : new Score();
                $score->setScore($score->getScore() + 10 );
                $score->setUser($game->getUser());
                
                $scoreRepository->save($score, true);

                $this->addFlash('success', 'Well Done ! You have a good memory !');
                return $this->render('game/success.html.twig', [
                    'user' => $game->getUser(),
                ]);
            } 
            $gameRepository->save($game, true);

            $this->addFlash('danger', 'Fail ! You don\'t have memory.. !');
            return $this->render('game/fail.html.twig', [
                'user' => $game->getUser(),
            ]);
        }
        
        return $this->render('game/result.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param mixed $colors
     * 
     * @return string
     */
    private function getSequence($colors): string
    {
        $sequence = null;
        foreach ($colors as $color) {
            $sequence .= substr($color->getName(), 0, 1); 
        }

        return $sequence;
    }
}
