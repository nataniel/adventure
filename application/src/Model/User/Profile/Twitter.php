<?php
namespace Main\Model\User\Profile;
use Main\Model\User\Profile;

/**
 * @Entity
 */
class Twitter extends Profile
{
    public function getTypeName()
    {
        return "Twitter";
    }
}