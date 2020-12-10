<?php

include 'bootstrap/Psr4Autoload.php';
$config  = include 'config/config.php';

include 'bootstrap/Start.php';
Start::init();

include 'bootstrap/maps.php';
Start::router();



