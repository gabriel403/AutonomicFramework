<?php

abstract class Autonomic_Controller {

    private $_view;

    function  __construct() {
    }

    function _setView($view){
        $this->_view = $view;
    }

    function  __toString() {
        return $this->_view;
    }
}
?>
