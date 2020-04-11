<?php
namespace Main\Controller\Game;

use Main\Model\Game;
use E4u\Response;

abstract class AbstractController extends \Main\Controller\AbstractController
{
    /**
     * @return Game\Session|Response\Redirect
     */
    protected function getCurrentGameSession()
    {
        /** @var Game $game */
        $session = $_SESSION['game_session'];
        if (empty($session)) {
            return $this->redirectBackOrTo('/game/start');
        }

        return $session;
    }
}