<?php
namespace Main\Controller\Game;

use E4u\Application\View;
use Main\Model\Game;

class PlayController extends AbstractController
{
    public function indexAction()
    {
        $game = $this->getCurrentGame();
        $page = $game->getCurrentPage();

        if ($choice = (int)$this->getRequest()->getQuery('choice')) {
            try {
                $game->applyChoice($choice);
                return $this->redirectTo('/game/play');
            }
            catch (Game\Exception $ex) {
                return $this->redirectTo('/game/play', $ex->getMessage(), View::FLASH_ERROR);
            }
        }

        return [
            'title' => $page->getDescription(),
            'game' => $game,
        ];
    }
}