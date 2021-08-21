<?php
namespace Main\Form;

use E4u\Form;
use Main\Model\Game;
use Main\Model\User;

class StartGame extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\Select('game', [
                'label' => 'Wybierz grę',
                'required' => 'Wybierz grę.',
                'options' => $this->optionsGame(),
            ]),

            new Form\Element\TextField('player_name', [
                'label' => 'Nazwa gracza',
                'required' => 'Podaj nazwę gracza.',
            ]),

            new Form\Element\Submit('submit', 'Rozpocznij grę'),
        ]);

        return $this;
    }

    private function getUser(): ?User
    {
        return $this->getModel('user');
    }

    private function optionsGame(): array
    {
        $options = [];
        $user = $this->getUser();
        foreach (Game::findAll() as $game) {
            if ($game->isAvailableFor($user)) {
                $options[ $game->id() ] = $game->getName();
            }
        }

        return $options;
    }
}