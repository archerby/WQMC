<?php
/**
 * Basic quest events controller (like show current user level, check user answer, mark user as winner etc)
 *
 * @author KronuS
 * @version 1.0
 */
class command_quest extends command
{
    /**
     * Basic quest events handler
     * @param request $request
     * @return mixed|void
     */
    public function _do_execute(request $request) {
        $qid = $request->get('qid');
        if (is_null($qid)) {
            app::redirect('?');
        }
        else {
            $qid = intval($qid);
        }
        $quest = new quest($qid);
		$quest->load();
        // Quest is closed
        if (!$quest->get_is_open()) {
            app::redirect('?');
        }
        $user = new user();
        // User isn't authorized
        if (!$user->is_user_valid()) {
            app::redirect('?cmd=login');
        }
        else {
            // User not signed to quest
            if (!$user->is_signed_to_quest($quest->get_qid())) {
                $view = new view_start_quest();
                $view->set_quest($quest)->set_user($user)->render($request);
            }
            else {
                // All good - show quest info or current level data
                $user->set_current_qid($quest->get_qid());
				$signed_quests = $user->get_signed_quests();
				$user_current_level = $signed_quests[$quest->get_qid()]['current_level'];
				if ($user_current_level < 1) {
					// User has finished this quest already
                    app::redirect('?cmd=quest_complete&qid='.$quest->get_qid());
				}
				else {
					$answer = $request->get('answer');
                    $level = $quest->get_level_by_offset($user_current_level);
					if (is_null($answer)) {
                        $view = new view_level();
                        $view->set_user($user)->set_level($level)->set_quest($quest)->render($request);
					}
					else {
						// Proc provided answer
						$answer = (string)$answer;
						if ($level->validate_answer($answer, $user->get_uid())) {
							// Valid answer
							$user->inc_current_level($level->get_lid());
                            $signed_quests = $user->get_signed_quests();
                            if ($quest->get_levels_count() === $signed_quests[$quest->get_qid()]['current_level'] - 1) {
                                // Quest finished
                                $user->set_is_winner(true);
                                $user->save();
                                app::redirect('?cmd=quest_complete&qid='.$quest->get_qid());
                            }
                            else {
                                // Just go to another level
                                $user->save();
                                app::redirect('?cmd=quest&qid='.$quest->get_qid());
                            }
						}
						else {
							// Invalid answer
                            $this->_logger->push('Invalid answer.');
							//$level = $quest->get_level_by_offset($user_current_level);
							$view = new view_level();
                            $view->set_user($user)->set_level($level)->set_quest($quest)->render($request);
						}
					}
				}
            }
        }
    }
}