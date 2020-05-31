<?php
namespace Main\Controller\Admin;

use E4u\Application\View;
use E4u\Response;
use Main\Form;
use Main\Model\Game;

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

            return $this->redirectTo('/admin/games/pages/' . $game->id(),
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
            'title' => $game->getName() . ' - lista stron',
            'game' => $game,
        ];
    }

    public function editAction()
    {
        $game = $this->getGameFromParam();
        $form = new Form\EditGame($this->getRequest(), [ 'game' => $game, ]);

        if ($form->isValid()) {
            $game->save();
            return $this->redirectTo('/admin/games/pages/' . $game->id(),
                'Zmiany zostały zapisane.', View::FLASH_SUCCESS);
        }

        return [
            'title' => $game->getName() . ' - edycja',
            'game' => $game,
            'editGame' => $form,
        ];
    }

    /**
     * @return Response\Redirect|Game
     */
    private function getGameFromParam()
    {
        $id = $this->getParam('id');
        if (empty($id)) {
            return $this->redirectTo('/games', 'Brak identyfikatora gry.', View::FLASH_ERROR);
        }

        $game = Game::find($id);
        if (empty($game)) {
            return $this->redirectTo('/games', 'Nieprawidłowy identyfikator gry.', View::FLASH_ERROR);
        }

        $user = $this->getCurrentUser();
        $operator = $game->getOperatorFor($user);
        if (empty($operator)) {
            return $this->redirectTo('/games', 'Brak uprawnień do edycji gry.', View::FLASH_ERROR);
        }

        return $game;
    }
}