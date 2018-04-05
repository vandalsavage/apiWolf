<?php


    class ApiManager{

        public $folder_access;

        public function checkVersion(){
            
            $folder_access =  explode("/", urldecode($_SERVER['REQUEST_URI']));// PEGA O ARQUIVO DE REQUISIÇÃO

            if(count($folder_access)>2 and file_exists($folder_access[1]."/")){
                $this->folder_access = $folder_access;
                return true;
            }
            echo $this->getError();
            return false;

        }

        public function loadApi(){
            require_once($this->folder_access[1]."/index.php");
        }

        public function getError(){

            require_once './apiManager/Request.php';

            $arrAccepts = Request::getAccept();

            return Request::getErrorContentType($arrAccepts);
        }

        public function getAccept(){
            return 'application/json';
        }

    }
