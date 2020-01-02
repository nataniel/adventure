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

            $game = new Game($startGame->getValue('player_name'));
            $_SESSION['game'] = $game;

            return $this->redirectTo('/game/play', 'Gra została rozpoczęta.', View::FLASH_SUCCESS);

        }

        return [
            'title' => 'Rozpocznij grę',
            'startGame' => $startGame,
        ];
    }

    public function resetAction()
    {
        $current = $this->getCurrentGame();
        $new = new Game($current->getPlayerName());
        $_SESSION['game'] = $new;

        return $this->redirectTo('/game/play', 'Gra została zresetowana.', View::FLASH_SUCCESS);
    }
}