<?php

class Controllers_Foo extends Autonomic_Controller {

    function BarAction() {
        $this->_getView()->fred = "hehe";
    }

}

?>
