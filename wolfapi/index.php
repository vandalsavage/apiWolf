<?php

    require_once './apiManager/ApiManager.php';

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");

    if (isset(getallheaders()['Access-Control-Request-Method']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('HTTP/1.1 204 No Content');
        die();
    }

    $apiManager = new ApiManager();

    if( $apiManager->checkVersion() ){
        $apiManager->loadApi();
    }
