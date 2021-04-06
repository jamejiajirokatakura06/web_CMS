<?php
error_reporting(E_ERROR | E_WARNING);

$remote= true;
if($remote)
{

    define('CONFIG_DB_HOST', 'mysqldb');
    define('CONFIG_DB_USER', 'joyce');
    define('CONFIG_DB_PASS', 'joyce-111');
    define('CONFIG_DB_DBNAME','db_joyce');

}
else
{
    define('CONFIG_DB_HOST','localhost');
    define('CONFIG_DB_USER','web');
    define('CONFIG_DB_PASS','1234');
    define('CONFIG_DB_DBNAME','web-cms');
}