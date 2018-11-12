<?php
namespace Main\Form;

use E4u\Form;

class EditPage extends Form\Base
{
    public function init()
    {
        $this->addFields([

            new Form\Element\TextField('name', [
                'label' => 'Nazwa',
                'model' => $this->getModel('page'),
                'required' => 'Podaj nazwę strony',
            ]),

            new Form\Element\TextField('description', [
                'label' => 'Krótki opis',
                'model' => $this->getModel('page'),
                'required' => 'Podaj tytuł opisowy strony',
            ]),

            new Form\Element\TextArea('content', [
                'label' => 'Treść',
                'model' => $this->getModel('page'),
                'required' => 'Wpisz treść strony',
            ]),

            new Form\Element\TextField('status', [
                'label' => 'Zmiany statusów',
                'model' => $this->getModel('page'),
                'required' => 'Wpisz treść strony',
            ]),

            new Form\Element\Url('image', [
                'label' => 'Obrazek',
                'model' => $this->getModel('page'),
            ]),

            new Form\Element\Submit('submit', 'Zapisz zmiany'),
        ]);

        return $this;
    }
}