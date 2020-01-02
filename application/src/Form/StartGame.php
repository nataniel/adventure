<?php
namespace Main\Form;

use E4u\Form;

class StartGame extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\TextField('player_name', [
                'label' => 'Nazwa gracza',
                'required' => 'Podaj nazwę gracza.',
            ]),

            new Form\Element\Submit('submit', 'Rozpocznij grę'),
        ]);

        return $this;
    }
}