<?php
namespace Main\Controller\Admin;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        return $this->redirectTo('/admin/pages');
    }
}