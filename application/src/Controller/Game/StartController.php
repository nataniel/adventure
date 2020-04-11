<?php
namespace Main\Controller\Game;

use E4u\Application\View;
use Main\Form as Forms;
use Main\Model\Game;

class StartController extends AbstractController
{
    public function indexAction()
    {
        $startGame = new Forms\StartGame($this->getRequest());
        if ($startGame->isValid()) {

            $player = $startGame->getValue('player_name');
            $session = new Game\Session($player);

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
        $session = new Game\Session($current->getPlayerName());

        $_SESSION['game_session'] = $session;
        return $this->redirectTo('/game/play', 'Gra została zresetowana.', View::FLASH_SUCCESS);
    }
}