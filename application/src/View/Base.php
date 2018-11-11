<?php
namespace Main\View;

use E4u\Application\View\Html as E4uView;
use E4u\Authentication\Identity;
use Main\Model\User;
use Main\Helper;
use E4u\Form;

/**
 * Class Base
 * @package Main\View
 */
class Base extends E4uView
{
    protected function registerHelpers()
    {
        parent::registerHelpers();
    }

    /**
     * @return User|Identity
     */
    public function getCurrentUser()
    {
        return parent::getCurrentUser();
    }

    /**
     * @param  Form\Base $form
     * @param  bool $showLabels
     * @return Form\Builder\Bootstrap41
     */
    public function createBootstrapForm($form, $showLabels = false)
    {
        return new Form\Builder\Bootstrap41($form, $this, [
            'show_labels' => $showLabels,
            'current_locale' => $this->getLocale(),
        ]);
    }
}