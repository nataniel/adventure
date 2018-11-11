<?php
namespace Main\Controller;

use E4u\Application\Controller as E4uController;
use E4u\Application\View as E4uView;
use E4u\Authentication\Identity;
use Main\Configuration;
use Main\Model\User;
use Main\View;

abstract class AbstractController extends E4uController
{
    protected $defaultLayout = 'layout/default';
    protected $viewClass = View\Base::class;

    public function init($action)
    {
        if (Configuration::isSSLRequired() && !$this->getRequest()->isSSL()) {
            return $this->redirectTo('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }

        return null;
    }

    /**
     * @return User|Identity
     */
    public function getCurrentUser()
    {
        return parent::getCurrentUser();
    }

    /**
     * @return View\Base|E4uView
     */
    public function getView()
    {
        return parent::getView();
    }
}