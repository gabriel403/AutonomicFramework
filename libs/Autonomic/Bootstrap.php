<?php

class Autonomic_Bootstrap {

    function __construct() {
        //return $this;
    }

    function run() {
        $queryString = trim($_SERVER['QUERY_STRING']);
        $requestURI = strlen($queryString) > 0 ? str_replace("?$queryString", "", $_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'];
        $contmeth1 = explode("/", $requestURI);
        foreach ($contmeth1 as $key => $value) {
            if (strlen(trim($value))) {
                $contmeth[] = $value;
            }
        }
        !isset($contmeth) ? $contmeth = array("Index", "Index") : null;
        switch (count($contmeth)) {
            case 1:
                $controllerName = ucfirst($contmeth[0]);
                $method = "Index";
                $this->render($controllerName, $method);
                break;

            case 2:
                $controllerName = ucfirst($contmeth[0]);
                $method = ucfirst($contmeth[1]);
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
            trigger_error("No action called $action exists in class " . get_class($controller));
        }
        $controller->_setView(implode(DIRECTORY_SEPARATOR, array("Views", $controllerName, $method)) . ".phtml");
        echo "<br />";
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
    return true;
}
