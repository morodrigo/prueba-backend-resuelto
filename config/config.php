<?php
class Config
{
    public static $host = 'mariadb';
    public static $user = 'prueba_web';
    public static $password = '123456';
    public static $database = 'prueba';

    public static function getGoogleConfig()
    {
        $configJson = file_get_contents('config/client_secret_454381194323-5mbbtpk6ui2slk3fgbfrdob2od4hcvvd.apps.googleusercontent.com.json');
        $configArray = json_decode($configJson, true);

        return $configArray;
    }

}
