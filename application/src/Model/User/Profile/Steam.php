<?php
namespace Main\Model\User\Profile;
use Main\Model\User\Profile;

/**
 * @Entity
 */
class Steam extends Profile
{
    public function getTypeName()
    {
        return "Steam Community";
    }
}