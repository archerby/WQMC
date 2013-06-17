<?php
/**
 * User login controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_quest_stats extends command {
    /**
     * Stats viewer handler. Show how many users are on current quest's levels
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if (!$user->is_user_valid()) {
            app::redirect('?');
        }
        $qid = $request->get('qid');
        if (is_null($qid)) {
            app::redirect('?');
        }
        else {
            $qid = intval($qid);
        }
        $quest = new quest($qid);
        $quest->load();
        $view = new view_quest_stats();
        $view->set_quest($quest)->set_user($user)->render($request);
    }
}