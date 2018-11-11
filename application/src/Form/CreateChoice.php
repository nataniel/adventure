<?php
namespace Main\Form;

use E4u\Form;

class CreateChoice extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\TextField('name', [
                'label' => 'Nazwa',
                'model' => $this->getModel('choice'),
                'required' => 'Podaj nazwę wyboru',
            ]),

            new Form\Element\TextField('description', [
                'label' => 'Krótki opis',
                'model' => $this->getModel('choice'),
            ]),

            new Form\Element\Number('position', [
                'label' => 'Pozycja',
                'model' => $this->getModel('choice'),
            ]),

            new Form\Element\Number('target', [
                'label' => 'Strona docelowa',
                'model' => $this->getModel('choice'),
            ]),

            new Form\Element\Submit('submit', 'Dodaj wybór'),
        ]);

        return $this;
    }
}