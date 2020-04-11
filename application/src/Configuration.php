<?php
namespace Main;

use E4u\Configuration as E4uConfiguration;
use Laminas\Config\Config;

abstract class Configuration extends E4uConfiguration
{
    /**
     * @return Config
     */
    public static function facebookConfig()
    {
        return self::getConfigValue('facebook');
    }

    /**
     * @return Config
     */
    public static function googleConfig()
    {
        return self::getConfigValue('google');
    }

    /**
     * @return Config
     */
    public static function microsoftConfig()
    {
        return self::getConfigValue('microsoft');
    }

    /**
     * @return Config
     */
    public static function twitterConfig()
    {
        return self::getConfigValue('twitter');
    }

    /**
     * @return Config
     */
    public static function steamConfig()
    {
        return self::getConfigValue('steam');
    }
}