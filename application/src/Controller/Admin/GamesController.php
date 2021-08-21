<?php
namespace Main\Controller\Admin;

use E4u\Application\View;
use Main\Form;
use Main\Model\Game;
use Main\Model\Page;

class GamesController extends AbstractController
{
    public function indexAction()
    {
        $user = $this->getCurrentUser();
        $games = $user->getOperatedGames();

        $game = new Game([ 'created_by' => $user, ]);
        $form = new Form\CreateGame($this->getRequest(), [ 'game' => $game, ]);

        if ($form->isValid()) {
            $operator = new Game\Operator([ 'user' => $user, 'type' => Game\Operator::TYPE_OWNER ]);
            $game->addToOperators($operator)->save();

            $page = new Page([ 'name' => 'start', 'game' => $game ]);
            $page->save();

            $this->redirectTo('/admin/games/pages/' . $game->id(),
                'Gra została utworzona.', View::FLASH_SUCCESS);
        }

        return [
            'title' => 'Twoje gry',
            'createGame' => $form,
            'games' => $games,
        ];
    }

    public function pagesAction()
    {
        $game = $this->getGameFromParam();

        return [
            'title' => $game->getName(),
            'game' => $game,
        ];
    }

    public function editAction()
    {
        $game = $this->getGameFromParam();
        $form = new Form\EditGame($this->getRequest(), [ 'game' => $game, ]);

        if ($form->isValid()) {
            $game->save();
            $this->redirectTo('/admin/games/pages/' . $game->id(),
                'Zmiany zostały zapisane.', View::FLASH_SUCCESS);
        }

        return [
            'title' => $game->getName(),
            'game' => $game,
            'editGame' => $form,
        ];
    }

    public function removeAction()
    {
        $game = $this->getGameFromParam();
        $game->destroy();

        $this->redirectTo('/admin', sprintf('Gra %s została usunięta.', $game->getName()));
    }
}