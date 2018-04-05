<?php
ini_set('precision', 22);
$init1 = microtime(true);

//require 'autoload/autoload.php'; // ** Sugestão para jogar para dentro do próprio app init
//use App\App;  


header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
require_once 'App.php';
$app = new App();
$app->init();




$total = microtime(true) - $init1;
//echo $total;

/*$app->entender();

$app->autenticarUsuario();

$app->capturarInformacoes();

$app->processar();

$app->responder();*/











// ** Remanejando adicionar versões

/*require './autoload/autoload.php';

use auth\Authenticate;
new Authenticate();*/
