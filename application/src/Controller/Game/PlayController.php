<?php
namespace Main\Controller\Game;

use Main\Model\Game;

class PlayController extends AbstractController
{
    public function indexAction()
    {
        $game = $this->getCurrentGame();
        $page = $game->getCurrentPage();

        if ($choice = (int)$this->getRequest()->getQuery('choice')) {
            $game->applyChoice($choice);
            return $this->redirectTo('/game/play');
        }


        return [
            'title' => $page->getDescription(),
            'game' => $game,
        ];
    }
}