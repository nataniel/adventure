<?php
namespace Main\Controller\Game;

use Main\Model\Game;
use E4u\Response;

abstract class AbstractController extends \Main\Controller\AbstractController
{
    /**
     * @return Game|Response\Redirect
     */
    protected function getCurrentGame()
    {
        /** @var Game $game */
        $game = $_SESSION['game'];
        if (empty($game)) {
            return $this->redirectBackOrTo('/game/start');
        }

        return $game;
    }
}