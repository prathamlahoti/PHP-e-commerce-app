<?php

namespace App\components;

use App\errors\FileNotFoundException;

/**
 * Class Router
 * @package App\components
 */
class Router
{
    /**
     *
     * @var mixed|null array of routes defined as empty
     */
    private $routes = null;
    /**
     *
     * @var array request url
     */
    private $path = [];

    /**
     * Router constructor.
     *
     * @throws FileNotFoundException if the file wasn't found
     */
    public function __construct()
    {
        $file = ROOT.'/config/routes.php';
        if (!file_exists($file)) {
            throw new FileNotFoundException("Файл {$file} не найден");
        }
        $this->routes = require $file;
    }

    /**
     *
     * @return bool|string request uri
     */
    private function getURI()
    {
        return (!empty($_SERVER['REQUEST_URI'])) ? trim($_SERVER['REQUEST_URI'], '/'): false;
    }

    /**
     *
     * @param $uriPattern - url pattern for routes searching(array key)
     * @param $path       - route, which equals to pattern (array value)
     * @param $url        - formed url
     * @return void
     */
    private function makePath($uriPattern, $path, $url)
    {
        if(empty($uriPattern)) {
           $this->path = explode("/", $path);
        } else {
            $internalRoute = preg_replace("~$uriPattern~", $path, $url);
            $this->path = explode("/", $internalRoute);
        }
    }

    /**
     *
     * @return string controller name
     */
    private function makeControllerName()
    {
        return ucfirst(array_shift($this->path)).'Controller';
    }

    /**
     *
     * @return string action name
     */
    private function makeActionName()
    {
        return 'action'.ucfirst(array_shift($this->path));
    }

    /**
     * @return string controller name with namespace
     */
    private function makeControllersNamespace($controller)
    {
        return "App\\controllers\\".$controller;
    }

    /**
     *
     * @param $controllerName - controller name
     * @throws FileNotFoundException if the file wasn't found
     * @return void
     */
    private function checkControllerFile($controllerName)
    {
        $file = ROOT."/controllers/{$controllerName}.php";
        if (!file_exists($file)) {
            throw new FileNotFoundException("Файл {$file} не найден");
        }
        require $file;
    }

    /**
     *
     * @param $controllerObj - controller object
     * @param $actionName    - controller method(action)
     * @param $options       - method parameters
     * @return bool - the calling of special controller and its action with optional options
     */
    private function executeRequest($controllerObj, $actionName, $options)
    {
        call_user_func_array([$controllerObj, $actionName], $options);
    }

    /**
     *
     * @return void
     */
    public function run()
    {
        $url = ($this->getURI() !== false)? $this->getURI(): false;
        foreach ( $this->routes as $uriPattern => $path ) {
            if (preg_match("~$uriPattern~", $url)) {
                $this->makePath($uriPattern, $path, $url);
                $controllerName = $this->makeControllerName();
                $actionName = $this->makeActionName();
                $options = $this->path;
                $this->checkControllerFile($controllerName);
                $controllerName = $this->makeControllersNamespace($controllerName);
                $controllerObj = new $controllerName;
                $this->executeRequest($controllerObj, $actionName, $options);
                break;
                }
        }
    }
}
