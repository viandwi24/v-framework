<?php

/* -----------------------------------------------
 |  Unique Key
 | -----------------------------------------------
 | Menambah Tingkat Kerumitan CSRF Token Pada Web
 |
 | [Default] = "" atau unset.
*/
$config['uniq_key'] =  "";

/* -----------------------------------------------
 |  Base URL [OPSIONAL]
 | -----------------------------------------------
 | beberapa user mengalami kegagal yang selalu
 | menampilkan halaman 404, maka kalian dapat
 | merubah base url berikut untuk menuliskanya
 | secara manual.
 | 
 | NB :
 | Kosongin Base Url Berikut Untuk Mendapat 
 | Base Url Otomatis Dari Kami.
 |
 | [Default] = "" atau unset.
 |
 | [Example :]
 |   $config['base_url'] =  "localhost/release/1.4.2/";  #without protocal
 |   $config['base_url_protocol'] =  "http"; #protocol without :// => [http/https]
*/
 $config['base_url'] = "";
 $config['base_url_protocol'] = "";