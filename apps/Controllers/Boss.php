<?php

class Controllers_Boss extends Autonomic_Controller {

    function NoAction() {
        $this->_getView()->fred = "hehe";
    }

}

?>
