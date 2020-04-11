<?php
namespace Main\Model\User\Profile;
use Main\Model\User\Profile;

/**
 * @Entity
 */
class Google extends Profile
{
    public function getTypeName()
    {
        return "Google Account";
    }
}