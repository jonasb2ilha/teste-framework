<?php 

namespace Core;

class Redirect
{
    
	public static function route($url, $with = [])
    {
        if (count($with) > 0)
            foreach ($with as $key => $value)
                Session::set($key, $value);
        return header("location:http://localhost:8000$url");
    }

  

	public function back()
	{
		$previous = "javascript:history.go(-1)";

    	if (isset($_SERVER['HTTP_REFERER'])) {
    		$previous = $_SERVER['HTTP_REFERER'];
    	}

    	return header("location:{$previous}");
	}
}