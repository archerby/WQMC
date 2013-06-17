<?php

/**
 *
 *
 * @author KronuS
 * @version 1.0
 */
class command_profile extends command
{
    /**
     *
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        // User isn't authorized
        if (!$user->is_user_valid()) {
            app::redirect('?cmd=login');
        }
        $view = new view_profile();
        $view->set_user($user)->render($request);
    }
}