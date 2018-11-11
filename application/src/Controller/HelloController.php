<?php
namespace Main\Controller;

class HelloController extends AbstractController
{
    public function worldAction()
    {
        return [];
    }

    public function testAction()
    {
        $this->setLayout('layout/test');
        return $this->renderView('world');
    }
}