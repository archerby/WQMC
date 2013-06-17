<?php
/**
 *
 */
class command_admin_delete_level extends command {
    /**
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && $user->get_permission('can_delete_level')) {
                $lid = $request->get('lid');
                $question = new question($lid);
                $question->load();
                if ($question->is_valid()) {
                    $level_factory = new level_factory();
                    $level_factory->delete_level($lid);
                }
                app::redirect('?cmd=admin_quest_list');
            }
        }
    }
}