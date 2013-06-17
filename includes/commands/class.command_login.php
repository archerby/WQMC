<?php
/**
 * User login controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_login extends command {
    /**
     * Login handler. If login valid redirect to main page, if not - show login form
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            app::redirect('?');
        }
		$login = $request->get('login');
		$pass = $request->get('pass');
        if (!is_null($request->get('submit'))) {
            if (!is_null($login) && !is_null($pass)) {
                if ($user->auth($login, $pass)) {
                    app::redirect('?');
                }
                else {
                    $this->_logger->push('Auth failed.');
                }
            }
            else {
                $this->_logger->push('Login or password not set.');
            }
        }
        $view = new view_login();
        $view->render($request);
	}
}