<?php

class Autonomic_Bootstrap {

    function run() {
        $queryString = trim($_SERVER['QUERY_STRING']);
        $requestURI = strlen($queryString) > 0 ?
                str_replace("?$queryString", "", $_SERVER['REQUEST_URI']) :
                $_SERVER['REQUEST_URI'];

        $requestURI = str_ireplace(ROOT_URI, "", $requestURI);
        $controllerMethod1 = explode("/", $requestURI);

        foreach( $controllerMethod1 as $value ) {
            if( strlen(trim($value)) ) {
                $controllerMethod[] = $value;
            }
        }
        !isset($controllerMethod) ? $controllerMethod = array("Index", "Index") : null;

        switch( count($controllerMethod) ) {
            case 1:
                $controllerName = method_exists("Controllers_Index",
                                ucfirst($controllerMethod[0]) . "Action") ? "Index" : ucfirst($controllerMethod[0]);
                $method = method_exists("Controllers_Index",
                                ucfirst($controllerMethod[0]) . "Action") ? ucfirst($controllerMethod[0]) : "Index";
                break;

            case 2:
                $controllerName = ucfirst($controllerMethod[0]);
                $method = ucfirst($controllerMethod[1]);
                break;

            default:
                break;
        }

        $_SESSION['controller'] = $controllerName;
        $_SESSION['method'] = $method;

        $config = parse_ini_file("Configs/config.ini", true);
        foreach( $config as $section => $sectionvalues ) {
            if( Autonomic_Helpers_IsXHR::IsXHR() )
                continue;
            if( array_key_exists("everyrun", $sectionvalues) ) {
                if( is_array($sectionvalues["everyrun"]) ) {
                    foreach( $sectionvalues["everyrun"] as $functioncall ) {
                        $args = explode("::", $functioncall);
                        $args[0]::$args[1]();
                    }
                }
            }
        }

        $this->render($controllerName, $method);
    }

    function render( $controllerName, $method ) {
        $className = "Controllers_" . $controllerName;
        $controller = new $className();
        $action = $method . "Action";
        if( !method_exists($controller, $action) ) {
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

function __autoload( $className ) {
    $fileExists = false;
    $paths = explode("_", $className);
    $pathAndFile = implode(DIRECTORY_SEPARATOR, $paths) . ".php";

    $includePaths = explode(':', get_include_path());
    foreach( $includePaths as $path ) {
        if( file_exists(implode(DIRECTORY_SEPARATOR, array($path, $pathAndFile))) ) {
            require_once $pathAndFile;
            return true;
        }
    }
    throw new Exception("Unable to find $className in PHP PATH " . get_include_path() . " Tried $pathAndFile");
}
