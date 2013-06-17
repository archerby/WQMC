<?php

class view_navi extends view {

    protected $_user;
    protected $_quest;

    public function set_user(user $user) {
        $this->_user = $user;
        return $this;
    }

    public function set_quest(quest $quest) {
        $this->_quest = $quest;
        return $this;
    }

    function _do_render(request $request) {
        if (is_null($this->_user)) {
            echo __CLASS__.' user or quest are not set.';
            return;
        }
        echo '<ul>';
        echo '<li><a href="?">All quests</a></li>';
		if (!$this->_user->is_user_valid()) {
			echo '<li><a href="?cmd=login">Login</a></li>';
			echo '<li><a href="?cmd=register">Register</a></li>';
		}
        if ($this->_user->is_user_valid()) {
            if (!is_null($this->_quest)) {
                if ($this->_user->is_signed_to_quest($this->_quest->get_qid())) {
                    if ($request->get('cmd') != 'quest') {
                        echo '<li><a href="?cmd=quest&amp;qid='.$this->_quest->get_qid().'">Continue quest</a></li>';
                    }
                }
                else {
                        echo '<li><a href="?cmd=start_quest&amp;qid='.$this->_quest->get_qid().'">Start quest</a></li>';
                }
                echo '<li><a href="?cmd=quest_winners&amp;qid='.$this->_quest->get_qid().'">Winners</a></li>';
                echo '<li><a href="?cmd=quest_stats&amp;qid='.$this->_quest->get_qid().'">Stats</a></li>';
            }
            echo '<li><a href="?cmd=change_password">Change pass</a></li>';
            echo '<li><a href="?cmd=logout&amp;token='.$this->_user->get_token().'">Logout</a></li>';
        }
        echo '</ul>';
    }
}