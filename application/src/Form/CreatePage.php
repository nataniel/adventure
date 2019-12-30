<?php
namespace Main\Form;

use E4u\Form;

class CreatePage extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\TextField('name', [
                'label' => 'Nazwa (identyfikator)',
                'model' => $this->getModel('page'),
                'required' => 'Podaj nazwę strony',
            ]),

            new Form\Element\TextField('description', [
                'label' => 'Tytuł strony',
                'model' => $this->getModel('page'),
            ]),

            new Form\Element\TextField('content', [
                'label' => 'Treść',
                'model' => $this->getModel('page'),
                'required' => 'Wpisz treść strony',
            ]),

            new Form\Element\TextField('status', [
                'label' => 'Zmiany statusów',
                'model' => $this->getModel('page'),
            ]),

            new Form\Element\Url('image', [
                'label' => 'Obrazek (adres URL)',
                'model' => $this->getModel('page'),
            ]),

            new Form\Element\Submit('submit', 'Utwórz stronę'),
        ]);

        return $this;
    }
}