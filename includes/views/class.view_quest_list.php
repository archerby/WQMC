<?php

class view_quest_list extends view {
	protected $_quests;
	protected $_user;
	public function set_quests(array $quests) {
		$this->_quests = $quests;
		return $this;
	}
	public function set_user(user $user) {
		$this->_user = $user;
		return $this;
	}
	function _do_render(request $request) {
		if (is_null($this->_quests) || is_null($this->_user)) {
			echo __CLASS__.' quests not set.';
			return;
		}
		$this->_title = 'Quests list';
		ob_start();
		echo '<ul>';
        foreach($this->_quests as $quest) {
            if ($quest->get_is_open()) {
                echo '<li><a href="?cmd=quest&qid='.$quest->get_qid().'">'.$quest->get_name().'</a></li>';
            }
        }
        echo '</ul>';
		$this->_content = ob_get_clean();
		ob_start();
        $navi_view = new view_navi();
        $navi_view->set_user($this->_user)->render($request);
        $this->_navi = ob_get_clean();
        $this->_invitation = 'Hello, '.($this->_user->get_login()?$this->_user->get_login():'guest');
		$this->_prepare_template(config::get('default_theme_path').'template.php');
	}
}