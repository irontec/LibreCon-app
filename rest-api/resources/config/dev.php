<?php
require __DIR__ . '/prod.php';
$app['debug'] = true;
$app['log.level'] = Monolog\Logger::DEBUG;

$app['gearmand.path'] = 'path/to/gearman';

$app['photocall.path'] = '/path/to/images/folder';

$app['photocall'] = array(
        'path'=>'/path/to/images/folder',
        'url'=>'/url/to/images/folder');
