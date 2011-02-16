<?php

class Autonomic_Bootstrap {


    function run() {
        $queryString = trim($_SERVER['QUERY_STRING']);
        $requestURI = strlen($queryString) > 0 ? 
            str_replace("?$queryString", "", $_SERVER['REQUEST_URI']) :
            $_SERVER['REQUEST_URI'];
        
        $controllerMethod1 = explode("/", $requestURI);
        
        foreach ($controllerMethod1 as $value) {
            if (strlen(trim($value))) {
                $controllerMethod[] = $value;
            }
        }
        
        !isset($controllerMethod) ? $controllerMethod = array("Index", "Index") : null;
        
        switch (count($controllerMethod)) {
            case 1:
                $controllerName = ucfirst($controllerMethod[0]);
                $method = "Index";
                $this->render($controllerName, $method);
                break;

            case 2:
                $controllerName = ucfirst($controllerMethod[0]);
                $method = ucfirst($controllerMethod[1]);
                $this->render($controllerName, $method);
                break;

            default:
                break;
        }
    }

    function render($controllerName, $method) {
        $className = "Controllers_" . $controllerName;
        $controller = new $className();
        $action = $method . "Action";
        if (!method_exists($controller, $action)) {
            throw new Exception("No action called $action exists in class " 
                    . get_class($controller));
        }
        $controller->_setView(implode(DIRECTORY_SEPARATOR, 
                array("Layouts", "Views", $controllerName, $method)) . ".phtml");
        $controller->$action();
        @include implode(DIRECTORY_SEPARATOR, array("Layouts", "header")) . ".phtml";
        echo $controller->_getView();
        @include implode(DIRECTORY_SEPARATOR, array("Layouts", "footer")) . ".phtml";
    }

}

function __autoload($className) {
    $fileExists = false;
    $paths = explode("_", $className);
    $pathAndFile = implode(DIRECTORY_SEPARATOR, $paths) . ".php";
    
    $includePaths = explode(':', get_include_path());   
    foreach( $includePaths as $path) {
        if ( file_exists(implode(DIRECTORY_SEPARATOR, array($path, $pathAndFile))) )
        {
                require_once $pathAndFile;
                return true;
        }        
    }
    throw new Exception("Unable to find $className in PHP PATH " . get_include_path() . " Tried $pathAndFile");
}
