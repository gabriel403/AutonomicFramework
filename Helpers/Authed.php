<?php
/**
 * Description of Authed
 *
 * @author gabriel
 */
class Autonomic_Helpers_Authed {

	public function isAuthed() {
		Models_SessionModel::checkAndRedirect();
		$_SESSION['last_access'] = time();
	}

}

?>
