<?php

class Autonomic_Bootstrap {

    function __construct() {
        //return $this;
    }

    function run() {
        Zend_Debug::dump($_SERVER);
        Zend_Debug::dump($_REQUEST);
        $queryString = trim($_SERVER['QUERY_STRING']);
        $requestURI = strlen($queryString) > 0 ? str_replace("?$queryString", "", $_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'];
        $contmeth1 = explode("/", $requestURI);
        foreach ($contmeth1 as $key => $value) {
            if (strlen(trim($value))) {
                $contmeth[] = $value;
                ;
            }
        }
        Zend_Debug::dump($contmeth);
        switch (count($contmeth)) {
            case 1:
                $controllerName = ucfirst($contmeth[0]);
                $method = "Index";
                $this->checkAndCallControllerMethod($controllerName, $method);
                break;

            case 2:
                $controllerName = ucfirst($contmeth[0]);
                $method = ucfirst($contmeth[1]);
                $this->checkAndCallControllerMethod($controllerName, $method);
                break;

            default:
                break;
        }
    }

    function checkAndCallControllerMethod($controllerName, $method) {
        $className = "Controllers_" . $controllerName;
        $controller = new $className();
        $action = $method . "Action";
        if (!method_exists($controller, $action)) {
            trigger_error("No action called $action exists in class " . get_class($controller));
        }
        $controller->_setView(implode(DIRECTORY_SEPARATOR, array("Views", $controllerName, $method)));
        $method_call_return = call_user_func(array($controller, $action));
        echo $controller;
    }

}

function __autoload($className) {
    echo "I been called $className<br />";
    $fileExists = false;
    $paths = explode("_", $className);
    $pathAndFile = implode("/", $paths) . ".php";
    //require_once $pathAndFile;
    $handle = @fopen($pathAndFile, 'r', TRUE);

    if ($handle) {
        fclose($handle);
        // only require the class once
        require_once $pathAndFile;
        $fileExists = true;
        return true;
    }
    trigger_error("Unable to find $className in PHP PATH " . get_include_path() . " Tried $pathAndFile");
    return false;
}
