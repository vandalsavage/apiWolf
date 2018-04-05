<?php
    require 'autoload/autoload.php';
    //namespace App;
    
    use App\Core\Request;
    use App\Error;

    class App{
        private $class;// carrega a classe a ser carregada
        private $OutOptions;
        private $AppObject;

        public function init(){
            echo "string";
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
            //$this->InterpretRequest();
        }

        public function InterpretRequest(){

            if( RecursoUriExist() && MethdosAllowd() && HeaderRequireds() ){
                $this->AuthenticateUser();
            }

            $this->responder();

            /*Request::setHeaders();

            Request::uri();
            $this->loadResource(Request::uri());
            var_dump(Request::uri());*/
            /*if($this->ValidadeClassBeforeStart()){
                $this->AppObject->init($this);
            }*/

        }
        public function LoadRequest(){
            Request::GetResponse();// obtem o tipo de resposta solicitada
            Request::GetFilters();  // obtem filtros aplicados
            Request::GetData(); // seta a entrada de dados
        }
        public function AuthenticateUser(){
        }

        public function teste(){
            echo "Teste retorno";
        }


        private function LoadRequestURL(){
            $request = Request::Uri();  //  valida a solicitação da URL
            var_dump($request);
            // Primeira
            if(sizeof($request)>=2 && strtolower($request[1]) =='auth' ){
                if(sizeof($request)>=3){
                    $LoadFor = strtolower($request[2]);
                    switch($LoadFor){
                        case 'authenticate':
                            $this->class = "\\App\Auth\\Authenticate";
                        break;
                        case 'refreshtoken':
                            $this->class = "\\App\Auth\\RefreshToken";
                        break;
                        case 'token':
                            $this->class = "\\App\Auth\\Token";
                        break;
                        default:
                            Error::Set(404,'Not Found', 'Recurso não disponível na API');
                            return false;
                        break;
                        return true;
                    }
                    return true;
                }
                Error::Set(404,'Not Found', 'Recurso não disponível na API');
                return false;
            }
            else{
                if(sizeof($request)>=2){
                    $LoadFor = "\\App\Models\\". $request[1];
                    //echo $LoadFor;
                    if(class_exists($LoadFor)){
                        $this->class = $LoadFor;
                        return true;
                    }
                    Error::Set(404,'Not Found', 'Recurso não disponível na API');
                    return false;
                }
                Error::Set(404,'Not Found', 'Recurso não disponível na API');
                return false;
            }
            Error::Set(404,'Not Found', 'Recurso não disponível na API');
            return false;
        }
        private function ValidadeClassBeforeStart(){
            // primeira etapa verificar a requisição
            if($this->LoadRequestURL()){
                try{
                    $cls = new $this->class();
                    if ($cls instanceof App\Models\Models) {
                        $this->AppObject= $cls;
                        return true;
                    }
                    // registrar erro
                    Request::setHeaderCode(503);
                    return false;
                }catch(Exception $e){
                    // registrar exceção
                    Request::setHeaderCode(503);
                    return false;
                }

                Request::setHeaderCode(503);
                return false;
            }
            Request::setHeaderCode(404);
            return false;
        }

    }

    /*$app->entender(); = interpretRequest()

    $app->autenticarUsuario();

    $app->capturarInformacoes();

    $app->processar();

    $app->responder();*/
