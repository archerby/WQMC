<?php
/**
 * Admin section. 'Add level' controller
 * Show form, proceed provided level data, verify user's permissions and token
 *
 * @author KronuS
 * @version 1.0
 */
class command_admin_add_level extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_add_level')) {
                $qid = $request->get('qid');
                $quest = new quest($qid);
                $quest->load();
                if (!$quest->is_valid()) {
                    app::redirect('?cmd=admin_quest_list');
                }
                if (!is_null($request->get('submit'))) {
                    if (!$user->validate_token($request->get('token'))) {
                        app::redirect('?wrong_token');
                    }
                    else {
                        $level_factory = new level_factory();
                        if($level_factory->create_new_level($request->get('qid'), $request->get('task'), $request->get('hint'), $request->get('answer'), $request->get('oid')) === question::CREATING_COMPLETE) {
                            $user->save_new_token();
                            app::redirect('?cmd=admin_levels_list&qid='.$quest->get_qid());
                        }
                    }
                }
                $view = new view_admin_add_level();
                $view->set_user($user)->set_quest($quest)->render($request);
            }
        }
    }
}