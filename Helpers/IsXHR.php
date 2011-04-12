<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IsXHR
 *
 * @author gabriel
 */
class Autonomic_Helpers_IsXHR {
    public function IsXHR() {
        return strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], "xmlhttprequest") == 0;
    }
}

?>
