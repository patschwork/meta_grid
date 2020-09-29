<?php

namespace vendor\meta_grid\helper;

use Yii;

/**
 * Description of Helper
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 2.4
 * 
 */
class ApplicationVersion
{
    static $applicationVersion = "2.4.1";
    static $applicationName = "meta#grid";

    public function getVersion()
    {
        return self::$applicationVersion;
    }    
    
    public function getApplicationName()
    {
        return self::$applicationName;
    }

}