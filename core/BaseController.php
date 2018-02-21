<?php

namespace Core;

class BaseController
{
    private $viewPath;
    private $layout;
    protected $sanitized;
    protected $view;


     public function __construct()
    {
        $this->view = new \stdClass;
        
        if(Session::get('errors')){
            $this->errors = Session::get('errors');
            Session::destroy('errors');
        }
        if(Session::get('inputs')){
            $this->inputs = Session::get('inputs');
            Session::destroy('inputs');
        }
        if(Session::get('success')){
            $this->success = Session::get('success');
            Session::destroy('success');
        }
    }
  
    protected function view($viewPath, $layout= null, array $dataL = null) {

        $this->viewPath     = $viewPath;
        $this->layout       = $layout;
        
        if ($layout) {
            return $this->isOnLayout($dataL, $viewPath);
        } else {
            return $this->isNotLayout($dataL, $viewPath);
        }

    }

    protected function post(){
        $posts = $_POST;
        foreach ($posts as $name => $value) {
           $this->sanitized[$name] = filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
        }

       return (object) $this->sanitized;
    }

     protected function get(){
        $posts = $_GET;
        foreach ($posts as $name => $value) {
           $this->sanitized[$name] = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
        }

       return (object) $this->sanitized;
    }

    private function isOnLayout(array $data, $viewPath){
        $this->viewPath     = $viewPath;
         $load = function ($data) {
                
            if (empty($data) === false) {
                extract(func_get_arg(0));
            }

            require_once __DIR__ . "/../app/Views/template/layout-header.phtml";
            require_once __DIR__ . "/../app/Views/" . $this->viewPath . ".phtml";
            require_once __DIR__ . "/../app/Views/template/layout-footer.phtml";

        };

        $load($data);
        $load = $data = NULL;
       
    }

    private function isNotLayout($data, $viewPath){
        $this->viewPath     = $viewPath;
         $load = function ($data) {
                
            if (empty($data) === false) {
                extract(func_get_arg(0));
            }

            require_once __DIR__ . "/../app/Views/" . $this->viewPath . ".phtml";
        };

        $load($data);
        $load = $data = NULL;
       
    }

} 