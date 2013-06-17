<?php

class view_register extends view {
	function _do_render(request $request) {
		$this->_title = 'Register';
		ob_start();
		require_once('forms/register.php');
		$this->_content = ob_get_clean();
        ob_start();
        $view_navi = new view_navi();
        $view_navi->set_user(new user())->render($request);
		$this->_navi = ob_get_clean();
		$this->_prepare_template(config::get('default_theme_path').'template.php');
	}
}