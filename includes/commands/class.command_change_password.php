<?php
/**
 * User password changing controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_change_password extends command {
    /**
     * Password changing handler. If change correctly - redirect to main page, if not - show form
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if (!$user->is_user_valid()) {
            app::redirect('?');
        }
        $old_pass = $request->get('old_password');
        $new_pass = $request->get('new_password');
        $new_pass_c = $request->get('new_password_c');
        if (!is_null($request->get('submit'))) {
            if (!is_null($old_pass) && !is_null($new_pass) && !is_null($new_pass_c)) {
                if (is_string($old_pass) && is_string($new_pass) && is_string($new_pass_c) && trim($old_pass) !== '' && trim($new_pass) !== '' && trim($new_pass_c) !== '') {
                    if ($user->get_password() === user::gen_hash($old_pass, $user->get_salt())) {
                        if ($new_pass === $new_pass_c) {
                            if (!$user->validate_token($request->get('token'))) {
                                $this->_logger->push('Invalid token.');
                                app::redirect('?wrong_token');
                            }
                            $user->set_password(user::gen_hash($new_pass, $user->get_salt()));
                            $user->save();
                            app::redirect('?');
                        }
                        else {
                            $this->_logger->push('New password and it confirm should be equal.');
                        }
                    }
                    else {
                        $this->_logger->push('Invalid password.');
                    }
                }
                else {
                    $this->_logger->push('Invalid data types.');
                }
            }
            else {
                $this->_logger->push('All fields are required.');
            }
        }
        $view = new view_change_password();
        $view->set_user($user)->render($request);
    }
}