<?php
/* -----------------------------------------------
 |  Database Connection
 | -----------------------------------------------
 | Hubungkan Seluruh Class Yang Membutuhkan Database
 | Dengan Settingan Berikut
 |
*/
$config['db_driver'] = 'mysql';
$config['db_host'] = 'localhost';
$config['db_user'] = 'root';
$config['db_password'] = '';
$config['db_name'] = 'vframework';


/* -----------------------------------------------
 |  ERROR
 | -----------------------------------------------
 | Jika Anda menyetting false, hampir seluruh error
 | akan kami tangani dengan halaman "ERROR HANDLING".
 | Tetapi dalam beberapa kasus anda dapat memunculkan
 | error dalam bentuk string tanpa bantuan halaman 
 | "ERROR HANDLING".
 |
 | [Default] = false;
*/
$config['db_error_return_string'] = false;