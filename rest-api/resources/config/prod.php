<?php
$app['log.level'] = Monolog\Logger::ERROR;
$app['api.version'] = "v1";
$app['api.endpoint'] = "/api";


$app['gearmand.server'] = '0.0.0.0:4731';
$app['gearmand.path'] = 'path/to/gearman';

$app['photocall'] = array(
        'path'=>'/path/to/images/folder',
        'url'=>'/url/to/images/folder');
