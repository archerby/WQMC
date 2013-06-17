<?php
/**
 * User start quest controller
 *
 * @author KronuS
 * @version 1.0
 */
class command_start_quest extends command
{
    public function _do_execute(request $request) {
        $user = new user();
        if (!$user->is_user_valid()) {
            app::redirect('?cmd=login');
        }
        $qid = $request->get('qid');
        if (is_null($qid)) {
            app::redirect('?');
        }
        $quest = new quest($qid);
        $quest->load();
        if (!$quest->is_valid()) {
            app::redirect('?');
        }
        if (!$quest->get_is_open()) {
            app::redirect('?');
        }
        if (!$user->is_signed_to_quest($quest->get_qid())) {
            $user->sign_to_quest($quest->get_qid());
            app::redirect('?cmd=quest&qid='.$quest->get_qid());
        }
    }
}