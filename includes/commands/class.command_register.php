<?php
/**
 * User register controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_register extends command {
    /**
     * User register handler
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
        $pass_c = $request->get('pass_c');
        $captcha = $request->get('captcha');
        if (!is_null($request->get('submit'))) {
            if (isset($_SESSION['secpic']) && $_SESSION['secpic'] == $captcha) {
                if (!is_null($login) && !is_null($pass) && !is_null($pass_c) && is_string($login) && is_string($pass) && is_string($pass_c) && $pass === $pass_c) {
                    $user_factory = new user_factory();
                    $c = $user_factory->reg_new_user($login, $pass);
                    if($c === user::REG_COMPLETE) {
                        $this->_logger->push('Registration complete.');
                        app::redirect('?cmd=login');
                    }
                    else {
                        $this->_logger->push('Registration failed.');
                    }
                }
                else {
                    $this->_logger->push('Invalid data provided.');
                }
            }
            else {
                $this->_logger->push('Invalid captcha.');
            }
        }
        $view = new view_register();
		$view->render($request);
    }
}