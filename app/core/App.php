<?php

class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct(){
        $url = $this->parseURL();
         // controller
         if(isset($url[0]) && !empty($url[0])){
            $controllerFile = '../app/controllers/' . $url[0] . '.php';

            if(file_exists($controllerFile)){
                require_once $controllerFile;
                $this->controller = $url[0];
                $this->controller = new $this->controller;
                unset($url[0]);
            } else {
                
                echo "File controller tidak ditemukan: $controllerFile";
                exit;
            }
        } else {
            // Jika URL indeks ke-0 null atau tidak diatur, kembalikan ke controller default
            require_once '../app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
        }

        // method
        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // params
        if(!empty($url)){
            $this->params = array_values($url);
        }

        // jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }


    public function parseURL(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}