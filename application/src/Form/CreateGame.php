<?php
namespace Main\Form;

use E4u\Form;

class CreateGame extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\TextField('name', [
                'label' => 'Nazwa gry',
                'required' => 'Podaj nazwę gry',
                'model' => $this->getModel('game'),
            ]),

            new Form\Element\TextArea('description', [
                'label' => 'Krótki opis',
                'model' => $this->getModel('game'),
                'rows' => 3,
            ]),

            new Form\Element\Submit('submit', 'Utwórz grę'),
        ]);

        return $this;
    }
}