<?php
namespace Main\Controller\Admin;

use E4u\Response;
use E4u\Application\View;
use Main\Model\Game;
use Main\Model\User;

abstract class AbstractController extends \Main\Controller\AbstractController
{
    protected $requiredPrivileges = [ User\Privilege::EDIT_GAMES ];

    /**
     * @return Response\Redirect|Game
     */
    protected function getGameFromParam()
    {
        $id = $this->getParam('id');
        if (empty($id)) {
            return $this->redirectTo('/admin/games', 'Brak identyfikatora gry.', View::FLASH_ERROR);
        }

        $game = Game::find($id);
        if (empty($game)) {
            return $this->redirectTo('/admin/games', 'Nieprawidłowy identyfikator gry.', View::FLASH_ERROR);
        }

        $user = $this->getCurrentUser();
        $operator = $game->getOperatorFor($user);
        if (empty($operator)) {
            return $this->redirectTo('/admin/games', 'Brak uprawnień do edycji gry.', View::FLASH_ERROR);
        }

        return $game;
    }
}