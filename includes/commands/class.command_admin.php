<?php
/**
 * Admin access controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_admin extends command {
    /**
     * admin access handler
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access')) {
                $view = new view_admin();
                $view->set_user($user)->render($request);
            }
            else {
                app::redirect('?');
            }
        }
        else {
            app::redirect('?');
        }
    }
}