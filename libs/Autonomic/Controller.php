<?php

abstract class Autonomic_Controller {

    private $_view;
    private $_viewName;

    function __construct() {
        
    }

    function _setView($viewName) {
        $this->_viewName = $viewName;
        $this->_view = new Autonomic_View($viewName);
    }

    function _getView() {
        return $this->_view;
    }

    function _getViewName() {
        return $this->_viewName;
    }

    function __toString() {
        return $this->_view;
    }

}

?>
