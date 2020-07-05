<?php
require_once('DatabaseWorker.php');
require_once('DatabaseConectionService.php');

$config = require('config.php');
$worker = new DatabaseWorker();
$worker->connectToDatabase($config);
$worker->run();
echo 'FINISHED';

