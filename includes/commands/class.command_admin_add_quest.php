<?php
/**
 * Admin section. 'Add quest' controller
 * Show form, proceed provided quest data, verify user's permissions anf token
 *
 * @author KronuS
 * @version 1.0
 */
class command_admin_add_quest extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_add_quest')) {
                if (!is_null($request->get('submit'))) {
                    if (!$user->validate_token($request->get('token'))) {
                        app::redirect('?wrong_token');
                    }
                    else {
                        $quest_factory = new quest_factory();
                        if (in_array($request->get('theme'), app::get_themes())) {
                            if($quest_factory->create_new_quest($request->get('name'), $request->get('intro'), $request->get('outro'), $request->get('theme'), !is_null($request->get('is_open'))) === quest::CREATING_COMPLETE) {
                                $user->save_new_token();
                                app::redirect('?cmd=admin');
                            }
                        }
                        else {
                            app::redirect('?');
                        }
                    }
                }
                $view = new view_admin_add_quest();
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