<?php
/**
 * V-CMS - CMS yang cepat dan mudah untuk memanajemen halaman web.
 *
 * @package  V-CMS
 * @author   viandwi24 <fiandwi0424@gmail.com>
 */



/*|-------------------------------------------------------------------------|*/
define("APP_NAME", "V-FRAMEWORK");
define("APP_VERSION", "1.3.1 (Dalam Tahap Pengembangan)");
define("APP_AUTHOR", "Alfian Dwi <fiandwi0424@gmail.com>");

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

/*|-------------------------------------------------------------------------|*/


/*
|--------------------------------------------------------------------------
| Pengaturan Dasar V-CMS
|--------------------------------------------------------------------------
|
| Berisi pengaturan dasar v-cms, harap jangan mengedit jika tidak paham
| tentang ini.
|
*/
define('START', microtime(true));
define('BASE_URL', $protocol . '://' . dirname($_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
define('ROOT', __DIR__);
define('SYSTEM_ROOT', ROOT . '/system');
define('BOOT_CONFIG', SYSTEM_ROOT . '/boot_config.php');


/*
|--------------------------------------------------------------------------
| Composer Auto Load
|--------------------------------------------------------------------------
|
| Composer telah menyediakan manajemen package yang baik, jadi kami memakai
| Composer untuk membuat aplikasi ini agar lebih baik lagi.
|
*/
require __DIR__ . '/vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Persiapan V-CMS
|--------------------------------------------------------------------------
|
| Ini adalah proses untuk mempersiapkan aplikasi ini untuk berjalan, berisi
| banyak persiapan yang kami lakukan untuk kedepannya.
|
*/
use vframework\kernel\router;

$kernel = new router();
$kernel->boot();
