<?php
namespace Main\Model\User\Profile;

use Main\Model\User\Profile;

/**
 * @Entity
 */
class Microsoft extends Profile
{
    public function getTypeName()
    {
        return "Microsoft Account";
    }
}
