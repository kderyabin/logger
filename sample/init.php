<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require '../vendor/autoload.php';

use Kod\Logger;

ini_set('date.timezone', 'Europe/Paris');
// Init logger
$log = new Logger([
    'levelPriorityMin' => 'info',
]);
$login = '';
$log->info('Info message');
//// Error case
//$login = '';
//$exception = new Exception('Not an email');
//$log->error('Login failed: invalid login', [
//    'login' => $login,
//    'trace' => (string)$exception
//]);

