<?php
/**
 * Web Hooks
 * User: DEV
 * Date: 2018/10/22
 * Time: 14:36
 */

// $pwd = getcwd();
$pwd = '~/vueBus';
$command = 'cd '.$pwd.' && git pull';
$output = shell_exec($command);
var_dump($output);

// shell_exec('cd bus/token/app && npm run build');
