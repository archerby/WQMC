<?php
/**
 *
 */
class command_admin_levels_list extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && ($user->get_permission('can_add_level') || $user->get_permission('can_update_level') || $user->get_permission('can_delete_level'))) {
                $qid = $request->get('qid');
                if (is_null($qid)) {
                    app::redirect('?cmd=admin_quest_list');
                }
                $quest = new quest($qid);
                $quest->load();
                if(!$quest->is_valid()) {
                    app::redirect('?cmd=admin_quest_list');
                }
                $view = new view_admin_levels_list();
                $view->set_user($user)->set_quest($quest)->render($request);
            }
        }
    }
}