<?php

    class Request{

        public static function getErrorContentType($arrAccepts){
            header("HTTP/1.0 400 Bad Request");

            if( !isset($arrAccepts[0]) ){
                header("Content-Type: text/html; charset=UTF-8");
                return '400 Bad Request | Header Accept is not set';
            }
            
            $accept = $arrAccepts[0];

            if( $accept == 'application/json' || $accept == 'text/json' || isset($_GET['ilikejson']) ){
                header("Content-Type: application/json; charset=UTF-8");
                return json_encode([ 'error' => 'Bad Request', 'status' => 400]);
            }
            else if( $accept == 'text/html' ){
                header("Content-Type: text/html; charset=UTF-8");
                return '400 Bad Request';
            }
            else if( $accept == 'application/xml'  || $accept == 'text/xml' ){
                header("Content-Type: application/xml; charset=UTF-8");
                return '<menssage>Bad Request</menssage><status>400</status>';
            }
            else if( $accept == '*/*'  || $accept == 'text/xml' ){
                header("Content-Type: text/html; charset=UTF-8");
                return '400 Bad Request | Header Accept is not set';
            }
            return 'Accept Not Suported';
        }







        // função responsável por traduzir a entrada de dados accept
        public static function getAccept(){
            $accept = $_SERVER['HTTP_ACCEPT'];
            $return = array(); // cria array pra resposta
            //echo $accept;
            if (strpos($accept, ',') !== false) { // VERIFICA SE POSSUI VIRGULA PASSADA
                $separate = explode(",", $accept); // SEPARA EM ARRAY OS VALORES PELA VIRGULA
                //echo "tamanho: " . sizeof($separate);
                if(sizeof($separate)>=1){
                    foreach($separate as $key => $value){
                        if($value != "" || $value != null){
                            //echo "<br>Key: " . $key . " valor: ". $value;
                            $resp = self::SeparateValue($value);
                            $return[$resp['type']] = $resp['value'];
                        }
                    }
                }
                else{
                    // só possui um valor enviar para tratamento do ponto virgula
                    //echo "<br>Só um valor passado";
                    $resp = self::SeparateValue($accept);
                    $return[$resp['type']] = $resp['value'];
                }
            }
            else{
                // enviar para tratamento de ponto e virgula
                $resp = self::SeparateValue($accept);
                $return[$resp['type']] = $resp['value'];
            }
            //return (self::OrderResponse($return));
            return self::OrderResponse($return);
        }

        // recebe dados do tipo  application/xml;q=0.9
        private static function SeparateValue($String){
           $array = array();
            if (strpos($String, ';') !== false) { // VERIFICA SE POSSUI VIRGULA PASSADA
                // NESSE PASSO SÃO ENCONTRADOS ARQUIVOS SEPARADOS POR PONTO E VIRGULA
                $separate = explode(";", $String); //CRIA UM ARRAY SEPARANDO OS VALORES
                // separação por virgula requer tratamento a parte
                //$separate!= null xx && sizeof($separate)>=1 && $separate[0]!= null && $separate[0]!= ""
                if($separate!= null && sizeof($separate)>=1 && $separate[0]!= null && $separate[0]!= "" ){
                    // nesse passo a primeira posição do array não ser zerado ou nulo
                   //echo "<br>". $separate[0];
                   if(sizeof($separate)>=2){ // TESTA A SEGUNDA POSIÇÃO DO VETOR
                        // AQUI O PROCESSO TENTA VALIDAR O PESO ATRIBUIDO
                        //$array[$separate[0]]= self::SetValue($separate[1]); // SETA OS VALORES NO ARRAY
                        $array['type'] = $separate[0];
                        $array['value']= self::SetValue($separate[1]);
                        //self::SetValue($separate[1]);
                   }
                }
            }
            else{
                // NÃO FORAM ADICIONADOS PESOS NESTE CASO SÓ É ADICIONADO
                //$array[$String]=1;
                $array['type'] = $String;
                $array['value']= 1;
            }
            //var_dump($array);
            return $array;
        }

        // função ordena as respostas para solicitadas
        private static function OrderResponse($array){
            $valueArray = array();
            $keyArray = array();

            $cont = 0;
            foreach($array as $key => $value){
                $valueArray[$cont]= $value;
                $keyArray[$cont] = $key;
                $cont++;
            }
            $cont = 0;
            for($i=0; $i < sizeof($valueArray) ; $i++){
                for($j=$i+1; $j < sizeof($valueArray); $j++){
                    if(floatval($valueArray[$j])> floatval($valueArray[$i])){
                        $auxValue = floatval($valueArray[$i]);
                        $auxKey = $keyArray[$i];

                        $valueArray[$i] = floatval($valueArray[$j]);
                        $keyArray[$i] = $keyArray[$j];

                        $valueArray[$j]= floatval($auxValue);
                        $keyArray[$j] = $auxKey;
                    }
                }
            }
            return $keyArray;
        }

        // atribuição de valor
        private static function SetValue($String){
            //echo "<br>C:" . $String;
            if (strpos($String, '=') !== false) { // VERIFICA SE POSSUI ATRIBUIÇÃO DE IGUALDADE
                $separate = explode("=", $String); //CRIA UM ARRAY SEPARANDO OS VALORES
                if($separate!= null && sizeof($separate)>=2 && $separate[1]!= null && $separate[1]!= "" && strtolower($separate[1])!= "q"){
                    try {
                        $value = floatval($separate[1]);
                        if($value != 0){
                            return $value;
                        }
                        return 1; // erro de conversão
                    } catch (Exception $e) {
                        // error reporting
                        return 1;
                    }
                    return  $separate[1]; // RETORNA O VALOR SETADO
                    // nesse exemplo é tentando adicionar o valor respoondente
                }
                return 1;
            }
            return 1;
        }

    }
