<?php

require_once 'vendor/autoload.php';


/* require_once('DatabaseWorker.php'); */
/* require_once('DatabaseConectionService.php'); */

$app = new \App\HttpCodeWorker();
$app->run();
/* $config = require('config.php'); */
/* $worker = new DatabaseWorker(); */
/* $worker->connectToDatabase($config); */
/* $worker->run(); */
echo 'FINISHED';

