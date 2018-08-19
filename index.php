<?php
/**
 * VIFA FRAMEWORK - Framework PHP Sederhana Untuk Meringankan Pekerjaan
 *
 * @package  VIFA FRAMEWORK
 * @author   viandwi24 <fiandwi0424@gmail.com>
 */


/*--------------------------------------------------------*/
$protocol = 'http';
if (isset($_SERVER['HTTPS'])) {
    $protocol = 'https';
} else if (isset($_SERVER['HTTP_X_SCHEME'])) {
    $protocol = strtolower($_SERVER['HTTP_X_SCHEME']);
} else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
} else if (isset($_SERVER['SERVER_PORT'])) {
    $serverPort = (int)$_SERVER['SERVER_PORT'];
    if ($serverPort == 80) {
        $protocol = 'http';
    } else if ($serverPort == 443) {
        $protocol = 'https';
    }
}
/*--------------------------------------------------------*/
define('START', microtime(true));
define('BASE_URL', $protocol . '://' . dirname($_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
define('ROOT', __DIR__);
define('SYSTEM_ROOT', ROOT . '/system');


/*
|--------------------------------------------------------
| Composer
|--------------------------------------------------------
| Kami menggunakan composer untuk melakukan  autoloading
| dengan standar PSR-4.
|
*/
require __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------
| Vifa Framework Kernel
|--------------------------------------------------------
| Kami Juga menyediakan Kernel Di luar composer.
| 
|
*/
require SYSTEM_ROOT . '/kernel/boot.php';


/*
|--------------------------------------------------------
| Boot System
|--------------------------------------------------------
| Booting ke system vframework
|
*/
use vframework\router\kernel;

$kernel = new kernel();
$kernel->boot();