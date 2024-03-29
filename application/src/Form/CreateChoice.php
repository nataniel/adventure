<?php
namespace Main\Form;

use E4u\Form;

class CreateChoice extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\Number('target', [
                'label' => 'Strona docelowa',
                'model' => $this->getModel('choice'),
                'required' => 'Podaj kod strony docelowej',
            ]),

            new Form\Element\TextArea('description', [
                'label' => 'Krótki opis',
                'model' => $this->getModel('choice'),
                'rows' => 3,
            ]),

            new Form\Element\TextField('status', [
                'label' => 'Wymagany status',
                'model' => $this->getModel('choice'),
            ]),

            new Form\Element\Number('position', [
                'label' => 'Pozycja',
                'model' => $this->getModel('choice'),
            ]),

            new Form\Element\Submit('submit', 'Dodaj wybór'),
        ]);

        return $this;
    }
}