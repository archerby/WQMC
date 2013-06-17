<?php
/**
 * Quest list controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_admin_quest_list extends command
{
    /**
     * Get open quest list
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            if ($user->get_permission('have_admin_access') && ($user->get_permission('can_add_quest') || $user->get_permission('can_update_quest') || $user->get_permission('can_delete_quest'))) {
                $db = db::obtain();
                $query = 'SELECT * FROM '.db::real_tablename('quests').' ORDER BY qid';
                $r = $db->fetch_array_pdo($query);
                $quests = array();
                foreach($r as $k=>$q) {
                    $quests[$k] = new quest($q['qid']);
                    $quests[$k]->set_all_data($q);
                }
                $view = new view_admin_quest_list();
                $view->set_user($user)->set_quests($quests)->render($request);
            }
        }
        else {
            app::redirect('?');
        }
    }
}