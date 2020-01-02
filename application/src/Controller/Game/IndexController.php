<?php
namespace Main\Controller\Game;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        return $this->redirectTo('/game/play');
    }
}