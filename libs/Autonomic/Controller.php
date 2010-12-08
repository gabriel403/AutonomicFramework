<?php

abstract class Autonomic_Controller {

    private $_view;

    function  __construct() {
    }

    function _setView($view){
        $this->_view = $view;
    }

    function _getView(){
        return $this->_view;
    }

    function  __toString() {
        return $this->_view;
    }
}
?>
