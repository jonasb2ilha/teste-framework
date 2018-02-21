<?php

namespace Core;

class Container
{
    public static function newController($controller)
    {
        $urlArray   = explode('/', $controller);

       
        if ($urlArray[0] == "Admin") {
            $objController = "App\\Controllers\\" . $controller;
            if ($objController) {
                return new $objController;
            }
        } else {
            $objController = "App\\Controllers\\" . $controller;
            if ($objController) {
                return new $objController;
            }
        }

        
    }

    public static function pageNotFound()
    {
        if (file_exists(__DIR__ . "/../app/views/404.phtml"))
        {
            return require_once __DIR__ . "/../app/views/404.phtml";
        }
        else
        {
            echo "Error 404: Page not found!";
        }
    }
}