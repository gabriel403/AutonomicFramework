<?php

class Autonomic_View {

    private $data = array();

    public function __construct($viewName) {
        $this->_viewName = $viewName;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        throw new Exception(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'], E_USER_NOTICE);
    }

    public function __toString() {
        ob_start();
        include $this->_viewName;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}

?>
