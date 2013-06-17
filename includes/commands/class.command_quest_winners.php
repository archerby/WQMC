<?php
/**
 * Quest winners controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_quest_winners extends command {
    /**
     * Quest winners
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $qid = $request->get('qid');
        if (is_null($qid)) {
            app::redirect('?');
        }
        $quest = new quest($qid);
        $quest->load();
        if (!$quest->get_is_open()) {
            app::redirect('?');
        }
        $user = new user();
        if ($user->is_user_valid()) {
            $user->load();
        }
		$view = new view_quest_winners();
        $view->set_user($user)->set_quest($quest)->render($request);
    }
}