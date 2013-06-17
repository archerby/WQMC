<?php
/**
 * User logout controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_logout extends command {
    /**
     * Logout handler
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid() && !is_null($request->get('token')) && $user->get_token() === $request->get('token')) {
            $user->logout();
        }
        app::redirect('?');
    }
}