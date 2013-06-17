<?php
/**
 *
 */
class command_admin_update_level extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_update_level')) {
                $lid = $request->get('lid');
                $level = new question($lid);
                $level->load();
                if (!$level->is_valid()) {
                    app::redirect('?cmd=admin_quest_list');
                }
                if (!is_null($request->get('submit'))) {
                    if (!$user->validate_token($request->get('token'))) {
                        app::redirect('?wrong_token');
                    }
                    else {
                        $level->set_data($request->to_array());
                        $level->save();
                        $user->save_new_token();
                        $this->_logger->push('Level updated.');
                        app::redirect('?cmd=admin_update_level&lid='.$level->get_lid());
                    }
                }
                $quest = new quest($level->get_qid());
                $quest->load();
                $view = new view_admin_update_level();
                $view->set_user($user)->set_quest($quest)->set_level($level)->render($request);
            }
        }
        else {
            app::redirect('?');
        }
    }
}