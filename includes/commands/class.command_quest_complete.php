<?php
/**
 * User finish quest controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_quest_complete extends command
{
    /**
     * User finish quest handler
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $user = new user();
        if ($user->is_user_valid()) {
            $user->load_signed_quests();
        }
        else {
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
        if (!$quest->get_is_open()) {
            app::redirect('?');
        }
        $user->set_current_qid($quest->get_qid());
        if ($user->is_signed_to_quest($quest->get_qid())) {
            if ($user->get_is_winner()) {
                $view = new view_quest_compete();
                $view->set_user($user)->set_quest($quest)->render($request);
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