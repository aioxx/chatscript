<?php
include 'functions/database/Medoo.php';
use Medoo\Medoo;
class DB
{
    private static $instance = null;
    public static function connect() {
        if (!self::$instance) {
            self::$instance = new Medoo($GLOBALS["database_info"]);
        }
        return self::$instance;
    }
    private function __clone() {}

    private function __construct() {}
}
?>
