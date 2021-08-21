<?php
namespace Main\Controller\Game;

use E4u\Application\View;
use Main\Form as Forms;
use Main\Model\Game;

class StartController extends AbstractController
{
    public function indexAction()
    {
        $startGame = new Forms\StartGame($this->getRequest(), [ 'user' => $this->getCurrentUser() ]);
        if ($startGame->isValid()) {

            $game = $startGame->getValue('game');
            $player = $startGame->getValue('player_name');
            $session = new Game\Session($game, $player);

            $_SESSION['game_session'] = $session;
            return $this->redirectTo('/game/play', 'Gra została rozpoczęta.', View::FLASH_SUCCESS);

        }

        return [
            'title' => 'Rozpocznij grę',
            'startGame' => $startGame,
        ];
    }

    public function resetAction()
    {
        $current = $this->getCurrentGameSession();
        $session = new Game\Session($current->getGame(), $current->getPlayerName());

        $_SESSION['game_session'] = $session;
        return $this->redirectTo('/game/play', 'Gra została zresetowana.', View::FLASH_SUCCESS);
    }

    public function leaveAction()
    {
        unset($_SESSION['game_session']);
        return $this->redirectTo('/game/start', 'Opuściłeś aktualną grę', View::FLASH_MESSAGE);
    }
}