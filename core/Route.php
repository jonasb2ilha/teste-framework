<?php 

namespace Core;

use App\Config;
use Core\Container;
use Core\Redirect;
use Core\Session;


class Route 
{
	private $routes;

	public function __construct(array $routes)
	{
		$this->setRoutes($routes);
		return $this->run();
	}

	private function setRoutes($routes)
	{
		foreach ($routes as $route)
        {

            $explode = explode('@', $route[1]);

            if (count($route) > 2) {

            	foreach ($route[2] as $key => $value) {

	            	if ($key == 'middleware' && $value == Config::auth()) {

	            		$url = $this->getUrl();

	            		if ($url == $route[0]){
                            
                            if (!session('logged')) {
                                flash('message', 'Area restrita, realize o login para continuar');
                                return Redirect::route('/login');
                            }

                            $role = session('user')['role'] == 1 ? Config::roleUser() : Config::roleAdmin();
                            
                            if ($route[2]['role']) {
                              
                                if ($route[2]['role'] != $role) {
                                    flash('message', 'Area restrira a administradores');
                                    return Redirect::route('/login');
                                }


                            }
                            
	            		}
	            		$explode = explode('@', $route[1]);
	            		$r = [$route[0], $explode[0], $explode[1], $value];
	            		$newRoutes[] = $r;
	            		$newRoutes;

	            	
	            	} 

	            }

            } else {

            	$explode = explode('@', $route[1]);
	            $r = [$route[0], $explode[0], $explode[1]];
	            $newRoutes[] = $r;
	            $newRoutes;

            }
            
        }
		$this->routes = $newRoutes;
	}

	private function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    private function getRequest()
    {
        $obj=new \stdClass;
        
        $get=(object)$_GET;
        $post=(object)$_POST;

        $obj->get=$get;
        $obj->post=$post;
        
        return $obj;   
    }

    private function run()
    {
        $url        = $this->getUrl();
        $urlArray   = explode('/', $url);

        foreach ($this->routes as $route)
        {
            $routeArray = explode('/', $route[0]);
            $param = [];
            for($i =0; $i < count($routeArray); $i++)
            {
                if ((strpos($routeArray[$i], "{") !== false) && (count($urlArray) == count($routeArray)))
                {
                    $routeArray[$i] = $urlArray[$i];
                    $param[] = $urlArray[$i];
                }
                $route[0] = implode($routeArray, '/');
            }

            if ($url == $route[0])
            {
                $found = true;
                $controller = $route[1];
                $action     = $route[2];
                break;
            }
        }

        if (isset($found)) 
        {
            $controller = Container::newController($controller);
            switch (count($param)) 
            {
                case 1:
                    $controller->$action($param[0], $this->getRequest());
                    break;
                case 2:
                    $controller->$action($param[0], $param[1], $this->getRequest());
                    break;
                case 3:
                    $controller->$action($param[0], $param[1], $param[2], $this->getRequest());
                    break;
                default:
                    $controller->$action($this->getRequest());
            }
        }
        else 
        {
            Container::pageNotFound();
        }
    }
}
