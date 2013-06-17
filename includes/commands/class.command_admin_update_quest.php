<?php
/**
 *
 */
class command_admin_update_quest extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_update_quest')) {
                $qid = $request->get('qid');
                if (is_null($qid)) {
                    app::redirect('?cmd=admin');
                }
                $quest = new quest($qid);
                $quest->load();
                if (!$quest->is_valid()) {
                    app::redirect('?cmd=admin');
                }
                if (!is_null($request->get('submit'))) {
                    if (!$user->validate_token($request->get('token'))) {
                        app::redirect('?wrong_token');
                    }
                    else {
                        $quest->set_all_data($request->to_array());
                        $quest->save();
                        $user->save_new_token();
                        $this->_logger->push('Quest updated.');
                        app::redirect('?cmd=admin_update_quest&qid='.$quest->get_qid());
                    }
                }
                $view = new view_admin_update_quest();
                $view->set_user($user)->set_quest($quest)->render($request);
            }
        }
        else {
            app::redirect('?');
        }
    }
}