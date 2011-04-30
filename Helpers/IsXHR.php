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
		if ( array_key_exists("HTTP_X_REQUESTED_WITH", $_SERVER) )
			return strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], "xmlhttprequest") == 0;
		return false;
	}

}

?>
