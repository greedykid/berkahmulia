<?php
/**
 * phpMyAdmin configuration
 */

declare(strict_types=1);

$cfg['blowfish_secret'] = 'c871df97bb0337c768910b503028d712'; /* 32-bytes random string */

/**
 * Servers configuration
 */
$i = 0;

$i++;
/* Authentication type */
$cfg['Servers'][$i]['auth_type'] = 'cookie';
/* Server parameters */
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = true;

/**
 * End of servers configuration
 */
$cfg['UploadDir'] = '';
$cfg['SaveDir'] = '';
