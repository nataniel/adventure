<?php
namespace Main\Controller\Admin;

use Main\Model\User;

abstract class AbstractController extends \Main\Controller\AbstractController
{
    protected $requiredPrivileges = [ User\Privilege::EDIT_GAMES ];
}